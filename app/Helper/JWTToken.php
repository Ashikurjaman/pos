<?php

namespace App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



class JWTToken {


    function CreateToken($userEmail){
        $key = env('JWT_KEY ');
        $payload=[
            'iss'=> 'Laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userEmail'=>$userEmail

        ];

        return JWT::encode($payload,$key,'HS256');


    }

    function VerifyToken($token){


        try {
            $key = env('JWT_KEY ');
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        return $decoded->userEmail;
        } catch (\Throwable $th) {
           return 'unauthorized';
        }
    }
}
