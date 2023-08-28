<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller
{
    //
    function userRegistration(Request $request){

        try {
            User::create([
                'firstName' =>$request->input('firstName'),
                'lastName' =>$request->input('lastName'),
                'email' =>$request->input('email'),
                'mobile' =>$request->input('mobile'),
                'password' =>$request->input('password')

        ]);

        return response()->json([
            'status'=>'success',
            'message'=>'Registration successful'
        ]);
        } catch (Throwable $th) {
            return response()->json([
                'status'=>'failure',
                'message'=> $th->getMessage()


            ]);
        }


    }


    function userLogin(Request $request){

       $count= User::where('email','=',$request->input('email'))
        ->where('password','=',$request->input('password'))

        ->count();
//  dd($count);

        if ($count==1) {
            # jwt token issue

            $token=JWTToken::CreateToken($request->input('email'));
            // dd($token);

            return response()->json([
                'status'=>'Success',
                'message'=>'Login Successfully',
                'token'=>$token
            ],status:200);
        }

        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'unauthorize'
            ],status:200);
        }
    }
}
