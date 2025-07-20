<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //
    public function index()
    {
        //$products = Product::all();
        $products = Product::with('stocks')->get();

        return response()->json($products);
    }
}
