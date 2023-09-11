<?php

namespace App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken {


    public static function CreateToken($userEmail,$userID):string{

        $key = env('JWT_KEY');
        $payload=[
            'iss'=> 'Laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userID'=>$userID,
            'userEmail'=>$userEmail

        ];

        return JWT::encode($payload,$key,'HS256');




    }


    public static function CreateTokenForSetPassword($userEmail):string{

        $key = env('JWT_KEY');
        $payload=[
            'iss'=> 'Laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*10,

            'userEmail'=>$userEmail

        ];

        return JWT::encode($payload,$key,'HS256');




    }



    public static function VerifyToken($token):object|string
    {


        try {
            $key = env('JWT_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded->userEmail;
        } catch (\Throwable $th) {
           return 'unauthorized';
        }
    }
}
