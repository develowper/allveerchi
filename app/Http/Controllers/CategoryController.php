<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Telegram;
use App\Http\Helpers\Util;
use App\Http\Helpers\Variable;
use App\Http\Requests\CarRequest;
use App\Http\Requests\CategoryRequest;
use App\Models\Admin;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{

    public function getTree()
    {
        return response()->json(['data' => Category::getTree()]);

    }

    public function edit(Request $request, $id)
    {

        $data = Car::with('agency')->with('driver')->find($id);
        $this->authorize('editAny', [Admin::class, $data]);
        return Inertia::render('Panel/Admin/Shipping/Car/Edit', [
            'statuses' => Variable::STATUSES,
            'data' => $data,


        ]);
    }

    public function create(CarRequest $request)
    {
        if (!$request->uploading) { //send again for uploading images
            return back()->with(['resume' => true]);
        }
//        $request->merge([
//            'status' => 'active',
//        ]);
        $data = Category::create($request->all());

        if ($data) {

            if ($request->img)
                Util::createImage($request->img, Variable::IMAGE_FOLDERS[Category::class], $data->id);

            $res = ['flash_status' => 'success', 'flash_message' => __('created_successfully')];
            Telegram::log(null, 'car_created', $data);
        } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
        return to_route('admin.panel.shipping.car.index')->with($res);

    }

    protected
    function searchPanel(Request $request)
    {
        $admin = $request->user();

        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $orderBy = $orderBy == 'agency' ? 'agency_id' : $orderBy;
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $status = $request->status;
        $query = Category::query()->select('*');

        if ($search)
            $query->where('name', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);


    }

    public function update(CategoryRequest $request)
    {
        $response = ['message' => __('response_error')];
        $errorStatus = Variable::ERROR_STATUS;
        $successStatus = Variable::SUCCESS_STATUS;
        $treeData = $request->tree_data;
        $cmnd = $request->cmnd;
        $data = Category::get();

        if ($cmnd) {

            switch ($cmnd) {

                case 'add':
                    $this->authorize('create', [Admin::class, $data, true]);
                    Category::create([
                        'parent_id' => $request->parent_id,
                        'name' => $request->name,
                        'status' => $request->checked ? 'active' : 'inactive',
                    ]);
                    if ($request->img)
                        Util::createImage($request->img, Variable::IMAGE_FOLDERS[Category::class], $data->id);

                    $tree = Category::getTree();
                    Telegram::log(null, 'category_created', $tree);
                    return response()->json(['tree_data' => $tree, 'message' => __('created_successfully')]);
                    break;
                case 'edit':
                    $data = Category::find($request->id);

                    foreach (['parent_id', 'name', 'status',] as $s) {
                        if ($s == 'status') {
                            if ($data->$s != ($request->checked ? 'active' : 'inactive'))
                                $this->authorize('edit', [Admin::class, $data, true, $s]);
                        } else if ($data->$s != $request->$s) {
                            $this->authorize('edit', [Admin::class, $data, true, $s]);
                        }
                    }
                    if ($data) {
                        if ($data->parent_id != $request->parent_id) {
                            $oldParent = Category::find($data->parent_id);
                            $newParent = Category::find($request->parent_id);
//                            if (!$newParent)
//                                return response()->json(['errors' => [sprintf(__("validator.invalid"), __('parent'))]], 422);
                            if ($oldParent) {
                                $oldParent->children = array_filter($oldParent->children ?? [], fn($i) => $i != $data->id);
                                $oldParent->save();
                            }
                            if ($newParent) {
                                $newParent->children = array_unique(array_merge($newParent->children ?? [], [$data->id]));
                                $newParent->save();
                            }

                        }
                        $data->parent_id = $request->parent_id;
                        $data->name = $request->name;
                        $data->status = $request->checked ? 'active' : 'inactive';
                        $data->save();
                    }
                    $tree = Category::getTree();
                    Telegram::log(null, 'category_edited', $tree);
                    return response()->json(['tree_data' => $tree, 'message' => __('updated_successfully')]);
                    break;
                case 'remove':
                    $data = Category::find($request->id);

                    $this->authorize('delete', [Admin::class, $data, true]);

                    if ($data) {

                        $categoryProducts = Product::whereJsonContains('categories', $data->id)->get();
                        foreach ($categoryProducts as $cp) {
                            $categories = array_filter($cp->categories, fn($id) => $id != $data->id);
                            $cp->categories = array_values($categories);
                            $cp->save();
                        }

                        if ($data->children)
                            return response()->json(['errors' => [__('categories_have_children_cant_delete')]], 422);

                        $data->delete();
                    }

                    $tree = Category::getTree();
                    Telegram::log(null, 'category_removed', $tree);
                    return response()->json(['tree_data' => $tree, 'message' => __('updated_successfully')]);
                    break;
                case  'upload-img' :

                    if (!$request->img) //  add extra image
                        return response()->json(['errors' => [__('file_not_exists')], 422]);
                    Util::createImage($request->img, Variable::IMAGE_FOLDERS[Category::class], $request->id);
                    return response()->json(['message' => __('updated_successfully')], $successStatus);

            }
        } elseif ($treeData) {


            $request->merge([
//                'cities' => json_encode($request->cities ?? [])
            ]);
            function updateChildren($items, $data, $parentId, $level)
            {
                foreach ($items as $item) {
                    $item = (object)$item;
                    $beforeItem = $data->find($item->id);
                    $beforeItem->parent_id = $parentId;
                    $beforeItem->level = $level;
                    $beforeItem->status = $item->checked ? 'active' : 'inactive';

                    updateChildren($item->children, $data, $item->id, $level + 1);
                    $beforeItem->children = collect($item->children)->pluck('id');
                    $beforeItem->save();

                }
            }

            updateChildren(collect($treeData), $data, null, 1);

            $res = Category::getTree();
            Telegram::log(null, 'category_edited', $res);
            return response()->json(['tree_data' => $res, 'message' => __('updated_successfully')]);


            return response()->json(__('response_error'), $errorStatus);
        }

        return response()->json($response, $errorStatus);
    }
}
