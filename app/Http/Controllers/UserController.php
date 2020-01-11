<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected function index()
    {
//        $data = $request->all();
//        if (isset($data['id'])) {
//            $id = $data['id'];
//            $user = User::find($id);
//
//            if (isset($user) && !empty($user)) {
////                return response
//            } else {
//
//            }
//
//        }
        $user = auth()->user();
       // $user = DB::table('users')->where('name', 'aa')->first();

        return response([
            'user' => $user
        ], 200);
    }

}
