<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Telegram;
use App\Http\Helpers\Util;
use App\Http\Helpers\Variable;
use App\Http\Requests\BrandRequest;
use App\Models\Admin;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{

    public function edit(Request $request, $id)
    {

        $data = Brand::find($id);
        $this->authorize('editAny', [Admin::class, $data]);
        return Inertia::render('Panel/Admin/Brand/Edit', [
            'statuses' => Variable::STATUSES,
            'brands' => Brand::select('id', 'name')->get(),
            'data' => $data,


        ]);
    }

    public function create(BrandRequest $request)
    {
        if (!$request->uploading) { //send again for uploading images
            return back()->with(['resume' => true]);
        }
//        $request->merge([
//            'status' => 'active',
//        ]);
        $data = Brand::create($request->all());

        if ($data) {

            if ($request->img)
                Util::createImage($request->img, Variable::IMAGE_FOLDERS[Brand::class], $data->id);

            $res = ['flash_status' => 'success', 'flash_message' => __('created_successfully')];
            Telegram::log(null, 'brand_created', $data);
        } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
        return to_route('admin.panel.brand.index')->with($res);

    }

    protected
    function searchPanel(Request $request)
    {

        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $status = $request->status;
        $query = Brand::query()->select('*');
        if ($search)
            $query->where('name', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);


    }

    public function update(BrandRequest $request)
    {
        $response = ['message' => __('response_error')];
        $errorStatus = Variable::ERROR_STATUS;
        $successStatus = Variable::SUCCESS_STATUS;
        $id = $request->id;
        $cmnd = $request->cmnd;
        $data = Brand::find($id);
        if (!starts_with($cmnd, 'bulk'))
            $this->authorize('editAny', [Admin::class, $data]);

        if ($cmnd) {
            switch ($cmnd) {
                case  'upload-img' :

                    if (!$request->img) //  add extra image
                        return response()->json(['errors' => [__('file_not_exists')], 422]);
                    Util::createImage($request->img, Variable::IMAGE_FOLDERS[Brand::class], $id);
                    return response()->json(['message' => __('updated_successfully')], $successStatus);

                case 'status-active':
                case 'status-inactive':
                    $data->status = explode('-', $cmnd)[1];
                    $data->save();
                    return response()->json(['message' => __('updated_successfully'), 'status' => $data->status,], $successStatus);


            }
        } elseif ($data) {


            $request->merge([
//                'cities' => json_encode($request->cities ?? [])
            ]);


            if ($data->update($request->all())) {

                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
//                dd($request->all());
                Telegram::log(null, 'brand_edited', $data);
            } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
            return back()->with($res);
        }

        return response()->json($response, $errorStatus);
    }
}
