<?php

namespace App\Http\Controllers;

use App\Models\ShippingMethod;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $methods = ShippingMethod::all();
        return response()->json($methods);
    }
}
