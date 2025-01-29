<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Variable;
use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public
    function index()
    {
        return Inertia::render('Shop/Index', [
            'price_types' => array_column(Variable::PRICE_TYPES, 'key')
        ]);

    }

    public
    function cartPage()
    {
        return Inertia::render('Shop/Cart', [
            'price_types' => array_column(Variable::PRICE_TYPES, 'key')

        ]);

    }

    public
    function shippingPage()
    {
        return Inertia::render('Shop/Cart', [
            'price_types' => array_column(Variable::PRICE_TYPES, 'key')

        ]);

    }

    public
    function paymentPage()
    {
        return Inertia::render('Shop/Cart', [
            'price_types' => array_column(Variable::PRICE_TYPES, 'key')

        ]);

    }
}
