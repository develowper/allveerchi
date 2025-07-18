<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Telegram;
use App\Http\Helpers\Util;
use App\Http\Helpers\Variable;
use App\Http\Requests\CatalogRequest;
use App\Models\Admin;
use App\Models\Agency;
use App\Models\Catalog;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CatalogController extends Controller
{

    public
    function view(Request $request, $id)
    {
        $data = Catalog::where('id', $id)->firstOrNew();
        $data->seo = strip_tags("$data->name_fa $data->pn");
        return Inertia::render('DZ/Catalog/View', [
            'back_link' => url()->previous(),
            'data' => $data,
        ]);

    }

    public function edit(Request $request, $id)
    {

        $data = Catalog:: find($id);

        $this->authorize('editAny', [Admin::class, $data]);

        return Inertia::render('Panel/Admin/Catalog/Edit', [
            'statuses' => Variable::STATUSES,
            'data' => $data,

        ]);
    }

    public function create(CatalogRequest $request)
    {
        if (!$request->uploading) { //send again for uploading images
            return back()->with(['resume' => true]);
        }
//        $request->merge([
//            'status' => 'active',
//        ]);


        $data = Catalog::create($request->all());

        if ($data) {
            if ($request->img) {
                Util::createImage($request->img, Variable::IMAGE_FOLDERS[Catalog::class], $data->id, null, null, false);
                $data->update(['image_url' => route('storage.catalogs') . "/$data->id.jpg"]);

            }
            $res = ['flash_status' => 'success', 'flash_message' => __('created_successfully')];
            Telegram::log(null, 'catalog_created', $data);
        } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
        return to_route('admin.panel.catalog.index')->with($res);

    }

    public function update(CatalogRequest $request)
    {
        $response = ['message' => __('response_error')];
        $errorStatus = Variable::ERROR_STATUS;
        $successStatus = Variable::SUCCESS_STATUS;
        $id = $request->id;
        $cmnd = $request->cmnd;
        $data = Catalog::find($id);
        if (!starts_with($cmnd, 'bulk'))
            $this->authorize('editAny', [Admin::class, $data]);

        if ($cmnd) {
            switch ($cmnd) {
                case  'upload-img' :

                    if (!$request->img) //  add extra image
                        return response()->json(['errors' => [__('file_not_exists')], 422]);
                    Util::createImage($request->img, Variable::IMAGE_FOLDERS[Catalog::class], $id, null, null, false);
                    $data->update(['image_url' => route('storage.catalogs') . "/$id.jpg"]);
                    return response()->json(['message' => __('updated_successfully')], $successStatus);

                case 'change-price':
                    $request->merge([
                        'changed' => $request->new_prices != $data->prices],
                    );
                    $request->validate(
                        [
                            'changed' => [Rule::in([true])],
                            'new_prices' => ['sometimes', 'array', 'min:0'],
                            'new_prices.*.from' => ['required', 'integer', 'gte:0'],
                            'new_prices.*.to' => ['required', 'integer', 'gt:new_prices.*.from'],
//                            'new_prices.*.type' => ['required', Rule::in(array_column(Variable::PRICE_TYPES, 'key'))],
                            'new_prices.*.price' => ['required', 'integer', 'gte:0'],

                        ],
                        [
                            'new_prices.*.from.required' => sprintf(__('validator.required'), __('from')),
                            'new_prices.*.from.numeric' => sprintf(__('validator.invalid'), __('from')),

                            'new_prices.*.to.required' => sprintf(__('validator.required'), __('until')),
                            'new_prices.*.to.numeric' => sprintf(__('validator.invalid'), __('until')),
                            'new_prices.*.to.gt' => sprintf(__('validator.gt'), __('until'), __('from')),

                            'new_prices.*.type.required' => sprintf(__('validator.required'), __('type')),
                            'new_prices.*.type.in' => sprintf(__('validator.invalid'), __('type')),

                            'new_prices.*.price.required' => sprintf(__('validator.required'), __('price')),
                            'new_prices.*.price.numeric' => sprintf(__('validator.invalid'), __('price')),
                            'new_prices.*.price.gte' => sprintf(__('validator.gt'), __('price'), 0),

                            'new_price.required' => sprintf(__('validator.required'), __('new_price')),
                            'new_price.numeric' => sprintf(__('validator.invalid'), __('new_price')),
                            'new_price.gt' => sprintf(__('validator.gt'), __('new_price'), __('new_auction_price')),

                            'changed' => __('not_any_change'),
                        ]);

                    $request->new_prices = collect($request->new_prices ?? [])->map(function ($i) {
                        $i['type'] = 'cash';
                        return $i;
                    });
                    $data->update(['prices' => $request->new_prices,]);

                    Telegram::log(null, 'catalog_edited', $data);

                    return response()->json(['message' => __('updated_successfully'), 'prices' => $request->new_prices], $successStatus);

            }
        } elseif ($data) {

            $request->merge([
//                'cities' => json_encode($request->cities ?? [])
            ]);

            if ($data->update($request->all())) {

                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
//                dd($request->all());
                Telegram::log(null, 'catalog_edited', $data);
            } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
            return back()->with($res);
        }

        return response()->json($response, $errorStatus);
    }

    public
    function searchPanel(Request $request)
    {

        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;

        $query = Catalog::query();

        if ($search)
            $query = $query
                ->where('name_fa', 'like', "%$search%")
                ->orWhere('name_en', 'like', "%$search%")
                ->orWhere('pn', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);
    }

    public
    function search(Request $request)
    {

        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;

        $query = Catalog::query()->where('status', 'active');

        if ($search)
            $query = $query
                ->where('name_fa', 'like', "%$search%")
                ->orWhere('name_en', 'like', "%$search%")
                ->orWhere('pn', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);
    }
}
