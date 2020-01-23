<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  $products = null;
//        $a = new \stdClass();
//        $a->rfdf = "155";
//        $a->fdf = "sdasd";
//        $a->ffer = 3;
        //return $products;
        // return $a;
        // $s = ['a' => $a];
        //return response($s);
        try {
            $products = Product::all();
            return response([
                'product' => $products
            ], 200);
        } catch (\Exception $e) {
            Log::info('error from getting product' . $e->getMessage());
            return response([
                'status' => 'Something went wrong',
            ], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required|alpha',
            'price' => 'required|numeric',
            'quantity' => 'required|integer'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response([
                'messages' => $validator->messages()
            ], 400);
        }
        $product = Product::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
        ]);
        //return $product;
        if ($product) {
            return response([
                'status' => 'Product sucessfully created',
                'product' => $product
            ], 200);
        } else {
            Log::error('Product create failed');
            return response([
                'status' => 'Something went wrong'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $rules = [
            'id' => 'required|integer|exists:products,id',
            'price' => 'required_if:quantity,null|numeric|min:0',
            'quantity' => 'required_if:price,null|numeric|min:1',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $validator->messages();
        }
        $product = Product::find($id);
        if (isset($product) && !empty($product)) {
            if ($product->update($data)) {
                return response([
                    'status' => 'price updated successfully'
                ], 200);
            } else {
                return response([
                    'status' => 'Something went wrong'
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [
            'id' => $id
        ];
        $rules = [
            'id' => 'required|integer|exists:products,id',
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'messages' => $validator->messages()
            ], 400);
        }
        $product = Product::find($id);
        if ($product && $product->delete()) {
            return response([
                'status' => 'order deleted successfully'
            ], 200);
        } else {
            return response([
                'status' => 'something went wrong'
            ], 400);
        }
    }
}
