<?php

namespace App\Http\Controllers;

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
        
    }
}
