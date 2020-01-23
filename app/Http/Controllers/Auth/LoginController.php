<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function login(Request $request) {

        $rules = [
            'email' => 'required|email|',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return $validator->messages();
        }
        $credentials = $request->only('email', 'password');
       // $token = JWTAUTH::attempt($credentials);
       // return $token;
        try {
            if (!$token = JWTAUTH::attempt($credentials)) {
                return response([
                    'error' => "Invalid Credentials"
                ], 401);
            }
        } catch (JWTException $e) {
            return response([
                'error' => 'Could not create token'
            ], 500);
        }
        $user = auth()->user();
        return response([
            'token' => $token,
            'user' => $user
        ], 200);
    }
}








