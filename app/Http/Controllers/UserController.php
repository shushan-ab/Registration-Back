<?php

namespace App\Http\Controllers;

use App\OrderedProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        return response([
            'user' => $user
        ], 200);
        //return $user;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();
//        if(!$user instanceof User) {
//            return response([
//                'msg' => 'user is not authenticated',
//            ], 400);
//        }
        // Is loged in user pressed on Add Card button
//        if($user['id'] !== $data['user_id']) {
//            return response([
//                'msg' => 'user is not authenticated',
//            ], 400);
//        }
        $rules = [
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|integer|exists:products,id',
            'number' => 'required|integer|min:1'
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response([
                'status' => $validator->messages()
            ],400);
        }
        Log::info('Validation passed');
        $productId = $data['product_id'];
        $quantity = $data['number'];
        $ordersQuantity = 0;
//        $product = Product::where('id', $productId)->where('quantity', '>=', $data['number'])->first();
        $product = Product::find($productId); //$product instanceof  Product
        if (isset($product) && !empty($product)) {
            if ($product->quantity >= $quantity ) {
                $user->load(['orderedProducts' => function ($query) use ($productId){
                    $query->where('product_id', $productId);
                }]);
                if (isset($user->orderedProducts) && count($user->orderedProducts) > 0) {
                    $ordered_product = $user->orderedProducts[0];
                   // $ordered_product->product_quantity += $quantity;
                   // return $ordered_product->product_quantity;
//                    $ordered_product->save();
                   // return $ordered_product;
                  //  $ordered_product->save();
                    $ordered_product->update([
                        'product_quantity' => $ordered_product->product_quantity + $quantity
                    ]);
                    if ($ordered_product) {
                        $user->load('orderedProducts');
                        foreach($user->orderedProducts as $prod) {
                            $ordersQuantity += $prod->product_quantity;
                        }
                        return response([
                            'status' => 'Order added to card',
                            'product' => $ordered_product,
                            'quantity' => $ordersQuantity
                        ], 200);
                    } else {
                        Log::error('Product save failed');
                        return response([
                            'status' => 'Something went wrong'
                        ], 400);
                    }
                } else {
                    $new_product = new OrderedProduct([
                        'product_id' => $productId,
                        'product_quantity' => $quantity,
                    ]);
                    $created = $user->orderedProducts()->save($new_product);
                    if ($created) {
                        $user->load('orderedProducts');
                        foreach($user->orderedProducts as $prod) {
                            $ordersQuantity += $prod->product_quantity;
                        }
                        return response([
                            'status' => 'Order added to card',
                            'product' => $new_product,
                            'quantity' => $ordersQuantity
                        ], 200);
                    } else {
                        return response([
                            'status' => 'Something went wrong'
                        ], 400);
                    }
                }
            } else {
                Log::error('Product quantity is less than customer wants');
                return response([
                    'status' => 'Please select less quantity'
                ],400);
            }
        } else {
            Log::error('Product not exists in db ');
            return response([
                'status' => 'Product not exists'
            ],404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [
            "product_quantity" => $request->quantity,
            "id" => $id
        ];
        //return $data;
        $rules = [
            "product_quantity" => 'required|integer',
            "id" => 'required|integer',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return $validator->messages();
        }
        $orderedProduct = OrderedProduct::find($id);
        if(isset($orderedProduct) && !empty($orderedProduct)) {  // Harc Karoyin
            $updatedProduct = $orderedProduct->update([
                'product_quantity' => $data['product_quantity']
            ]);
            if ($updatedProduct) {
                return response([
                    'status' => 'quantity updated successfully'
                ], 200);
            } else {
                return response([
                    'status' => 'Something went wrong',
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [
            'id' => $id
        ];
        $rules = [
            'id' => 'required|integer|exists:ordered_products,id'
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response([
                'status' => $validator->messages()
            ],400);
        }
        $product = OrderedProduct::find($id);
        if($product && $product->delete())
        {
            return response([
                'status' => 'order deleted successfully'
            ],200);
        } else {
            return response([
                'status' => 'something went wrong'
            ], 400);
        }
    }
    public function getProducts() {
        $products = Product::all();
        if (isset($products) && !$products->isEmpty()) {
            return response([
                'product' => $products
            ], 200);
        } else {
            return response([
                'status' => 'Can not get products',
            ], 400);
        }
    }
    public function getOrderedProducts()
    {
        $user = auth()->user();
        $orderedProducts = $user->load(['orderedProducts' => function ($query) {
            $query->select('id', 'user_id', 'product_id', 'product_quantity');
            $query->with(['product' => function ($q) {
                $q->select('id', 'name', 'price');
            }]);
        }]);
        if (isset($orderedProducts) && !empty($orderedProducts)) {
            return response([
                'product' => $user->orderedProducts
            ], 200);
        }
    }
}
