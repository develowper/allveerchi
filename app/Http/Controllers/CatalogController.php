<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
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
}
