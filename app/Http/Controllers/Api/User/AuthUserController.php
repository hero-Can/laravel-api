<?php

namespace App\Http\Controllers\Api\User;

use Response;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Support\Facades\Validator;


class AuthUserController extends Controller
{

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            "name"=>"required",
            "email"=>"required|string|email|unique:users,email",
            "password"=>"required"
        ]);

        if ($validator->fails()) {
           return response()->json($validator->errors());
        }

        $user = User::create([
            'name'=> $request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        // add some data to payload token when register
        $payload = JWTFactory::sub($user->id)
        ->email($user->email)
        ->role('user')
        ->country('us')
        ->make();
        $token = JWTAuth::fromUser($user,$payload);
        return response()->json(compact('token'));
    }

    public function login( Request $request){
        $validator = Validator::make($request -> all(),[
         'email' => 'required|string|email|max:255',
         'password'=> 'required'
        ]);

        if ($validator -> fails()) {
            # code...
            return response()->json($validator->errors());

        }
        $credentials = $request->only('email','password');
        // add some data to payload token when login
        $custom=[
            'email'=>$request->email,
            'role'=>'user',
        ];
        $payload = JWTFactory::sub('credentials')->data($custom)->make();
        $token = JWTAuth::attempt($credentials,$payload);
        try{
            if (! $token ) {
                # code...
                return response()->json( ['error'=> 'invalid username and password'],401);
            }
        }catch(JWTException $e){

          return response()->json( ['error'=> 'could not create token'],500);
        }


        return response()->json( compact('token'));

    }

    public function login2( Request $request){
        $validator = Validator::make($request -> all(),[
         'email' => 'required|string|email|max:255',
         'password'=> 'required'
        ]);

        if ($validator -> fails()) {
            # code...
            return response()->json($validator->errors());

        }
        $credentials = $request->only('email','password');
        // add some data to payload token when login
        $payload = JWTFactory::sub('credentials')->email($request->email)->make();
        $token = JWTAuth::attempt($credentials,$payload);
        try{
            if (! $token ) {
                # code...
                return response()->json( ['error'=> 'invalid username and password'],401);
            }
        }catch(JWTException $e){

          return response()->json( ['error'=> 'could not create token'],500);
        }


        return response()->json( compact('token'));

    }

 // public function login( Request $request){
    //     $validator = Validator::make($request -> all(),[
    //      'email' => 'required|string|email|max:255',
    //      'password'=> 'required'
    //     ]);

    //     if ($validator -> fails()) {
    //         # code...
    //         return response()->json($validator->errors());

    //     }
    //     $credentials = $request->only('email','password');
    //     // add some data to payload token when login
    //     $payload = JWTFactory::sub($credentials)
    //     ->role('user')
    //     ->country('us')
    //     ->make();
    //     $token = JWTAuth::attempt( $credentials,$payload);
    //     try{
    //         if (! $token ) {
    //             # code...
    //             return response()->json( ['error'=> 'invalid username and password'],401);
    //         }
    //     }catch(JWTException $e){

    //       return response()->json( ['error'=> 'could not create token'],500);
    //     }


    //     return response()->json( compact('token'));

    // }


    /** ******************************************** */

        // public function register(Request $request){
    //     $rules = [
    //         'name'=>'required',
    //         'email'=>'required|string|email|unique:users,email',
    //         'password'=>'required'
    //     ];

    //     $validator = Validator::make($request->all(),[
    //         "name"=>"required",
    //         "email"=>"required|string|email|unique:users,email",
    //         "password"=>"required"
    //     ]);

    //     if ($validator->fails()) {
    //        return response()->json($validator->errors());
    //     }

    //     User::create([
    //         'name'=> $request->name,
    //         'email'=>$request->email,
    //         'password'=>Hash::make($request->password),
    //     ]);

    //     $user = User::first();
    //     $token = JWTAuth::fromUser($user);
    //     return response()->json($token);
    // }
}
