<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            dd($token);

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



    function SendOTPCode(Request $request){

            $email =$request->input('email');
            $otp=rand(1000,9999);
            $count=User::where('email','=',$email)->count();

        if($count == 1){
            // otp send to user email

            Mail::to($email)->send(new OTPMail($otp));
            //otp code database insert korte hobe
            User::where ('email','=',$email)->update(['otp'=>$otp]);
        }

        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'unauthorize'
            ],status:200);
        }
    }
}
