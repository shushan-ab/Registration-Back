<?php

namespace App\Http\Controllers;

use App\OrderedProduct;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderedProductsController extends Controller
{
    public function order(Request $request)    {

        $data = $request->all();
        $user = auth()->user();

        $user->load(['orderedProducts' => function ($query) use ($data){
            $query->where('product_id', $data['product_id']);
        }]);
        if (isset($user->orderedProducts) && count($user->orderedProducts) > 0) {
            $ordered_product = $user->orderedProducts[0];
            $total_quantity = $ordered_product->product_quantity + $data['number'];
            $product = $ordered_product->update([
                'product_quantity' => $total_quantity
            ]);
        } else {
            $new_product = new OrderedProduct([
                'product_id' => $data['product_id'],
                'product_quantity' => $data['number'],
            ]);
            $product = $user->orderedProducts()->save($new_product);
        }
        return response([
            'status' => 'sucessfully created',
            'product' => $product
        ], 201);
    }
    public function get(){

        $user = auth()->user();
        $user->load(['orderedProducts' => function($query){
            $query->select('id','user_id','product_id','product_quantity');
            $query->with(['product' => function($q){
                $q->select('id','name','price');
            }]);
        }]);

        return response ([
            'product' => $user->orderedProducts
        ],200);
   }

   public function delete($id){
        if(OrderedProduct::where('id','=',$id)->delete());

       // $orderedProducts = OrderedProducts::with('product')->get(); //harc
        {
            return response([
            'deleted order' => 'order deleted successfully'
            ],200);
        }
             //DB::table('users')->where('id', '=', $id)->delete();


   }
}
