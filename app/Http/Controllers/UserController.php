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
        ],200);
        } catch (Throwable $th) {
            return response()->json([
                'status'=>'failure',
                'message'=> $th->getMessage()


            ],401);
        }


    }


    function userLogin(Request $request){

       $count= User::where('email','=',$request->input('email'))
        ->where('password','=',$request->input('password'))
        ->select('id')->first();
//  dd($count);

        if ($count!==null) {
            # jwt token issue

            $token=JWTToken::CreateToken($request->input('email'),$count->id);


            return response()->json([
                'status'=>'Success',
                'message'=>'Login Successfully',
                'token'=>$token
            ],status:200)->cookie('token',$token,60*24*30);
        }

        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'unauthorize'
            ],status:401);
        }
    }



    function SendOTPCode(Request $request){

            $email =$request->input('email');
            $otp=rand(1000,9999);
            $count=User::where('email','=',$email)->count();
            // dd($count);
        if($count == 1){
            // otp send to user email
            // dd($otp);
            Mail::to($email)->send(new OTPMail($otp));
            //otp code database insert korte hobe
            User::where ('email','=',$email)->update(['otp'=>$otp]);
            // dd($otp);

            return response()->json([
                'status'=>'Success',
                'message'=>'Please check mail box 4 digit code send to your inbox.'
            ],status:200);
        }

        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'unauthorize'
            ],status:200);
        }
    }


    function VerifyOtp(Request $request){
        $email= $request->input('email');
        $otp= $request->input('otp');

        $count= User::where('email','=',$email)->
        where('otp','=',$otp)->count();

        if($count==1){
            // Database e otp update
            User::where('email','=',$email)->
        where(['otp'=>'0']);

            // password reset jonno token issue

            $token=JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status'=>'Success',
                'message'=>'Otp verification successfully',
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
