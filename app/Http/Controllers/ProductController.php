<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->all();
        $product = Product::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],

        ]);
        return response([
            'status' => 'sucessfully created',
            'product' => $product
        ], 201);
    }

    public function get(){
        $product = Product::all();
        return response([
            'status' => 'sucessfully created',
            'product' => $product
        ], 200);
    }
}

