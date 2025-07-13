<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Telegram;
use App\Http\Helpers\Variable;
use App\Http\Requests\AccessRequest;
use App\Models\Admin;
use App\Models\Access;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccessController extends Controller
{
    public function edit(Request $request, $id)
    {

        $data = Access::find($id);
        $this->authorize('edit', [Admin::class, $data]);

        $data->accesses = Access::getTree($data->accesses);
        return Inertia::render('Panel/Admin/Access/Edit', [

            'data' => $data,


        ]);
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
        $query = Access::query()->select('*');
        if ($search)
            $query->where('name', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);


    }

    private function allChildrenChecked(array $children): bool
    {
        foreach ($children as $child) {
            if (empty($child['checked'])) {
                return false;
            }

            $grandChildren = $child['children'] ?? [];
            if (!empty($grandChildren) && !$this->allChildrenChecked($grandChildren)) {
                return false;
            }
        }

        return true;
    }

    function collectCheckedKeys(array $accesses): array
    {
        $res = [];
        foreach ($accesses as $access) {
            $children = $access['children'] ?? [];
            if (
                (!empty($children) && $this->allChildrenChecked($children)) ||
                (empty($children) && !empty($access['checked']))
            ) {
                $res[] = $access['key'];
            }
            $res = array_merge($res, $this->collectCheckedKeys($children));
        }
        return $res;
    }

    public function create(AccessRequest $request)
    {
        $admin = $request->user();
        $accesses = $request->accesses;
        $res = $this->collectCheckedKeys($accesses);

        $data = Access::create(['name' => $request->name, 'agency_level' => $request->agency_level, 'accesses' => $res]);

        if ($data) {

            $res = ['flash_status' => 'success', 'flash_message' => __('created_successfully')];
            Telegram::log(null, 'access_created', $data);
        } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
        return to_route('admin.panel.access.index')->with($res);

    }

    public function update(AccessRequest $request)
    {
        $response = ['message' => __('response_error')];
        $errorStatus = Variable::ERROR_STATUS;
        $successStatus = Variable::SUCCESS_STATUS;
        $id = $request->id;
        $cmnd = $request->cmnd;
        $data = Access::find($id);
        if (!starts_with($cmnd, 'bulk'))
            $this->authorize('edit', [Admin::class, $data]);

        if ($cmnd) {
            switch ($cmnd) {


            }
        } elseif ($data) {

            $res = $this->collectCheckedKeys($request->accesses);

            $request->merge([
                'accesses' => $res
            ]);

            if ($data->update($request->all())) {

                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
//                dd($request->all());
                Telegram::log(null, 'access_edited', $data);
            } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
            return back()->with($res);
        }

        return response()->json($response, $errorStatus);
    }

    public function delete(Request $request, $id)
    {
//        $id = $request->id;

        $cmnd = $request->cmnd;
        $data = Access::find($id);
        if (!starts_with($cmnd, 'bulk'))
            $this->authorize('delete', [Admin::class, $data]);

        if ($data->delete()) {
            Telegram::log(null, 'access_deleted', $data);
            return response()->json(['message' => __('done_successfully'),], Variable::SUCCESS_STATUS);
        }
        return response()->json(['message' => __('response_error'),], Variable::ERROR_STATUS);


    }

}
