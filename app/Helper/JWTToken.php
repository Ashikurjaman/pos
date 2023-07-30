<?php

namespace App\Helper;



class JWTToken {


    function CreateToken(){
        $key = env('JWT_KEY ');
        $payload=[
            'iss'=> 'Larave token'
        ];
    }

    function VerifyToken(){

    }
}
