<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'string', 'min:8', 'confirmed'],
//        ]);
//    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|string|max:255|email|unique:users',
                'password' => 'required|string|max:255|',
                'confirmation' => 'required|same:password',
                'address' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->messages();
            }
            $manager = Role::where('name', $request['role'])->first();
           // return 66;
            $user = User::create([
                'name' => $request['name'],
                'surname' => $request['surname'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'confirmation' => Hash::make($request['confirmation']),
                'address' => $request['address'],
                'role_id' => $manager->id
            ]);
            Log::info('user: '.json_encode($user));
           // return $user;
        if($user)
        {
            return response([
                'status' => 'sucessfully created',
                'user' => $user
            ]);
        } else {
            Log::error('user creating failed');
            return response([
                'msg' => 'Something went wrong'
            ],400);
        }

//        if (isset($user)) {
//            return response([
//                'status' => 'sucessfully created',
//                'user' => $user
//            ], 201);
//        } else {
//            return response([
//                'status' => 'User does not created'
//            ], 404);
//        }
        } catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
