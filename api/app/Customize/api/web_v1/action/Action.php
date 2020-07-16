<?php


namespace App\Customize\api\web_v1\action;


class Action
{
    public static function success($message = '' , $data = '' , int $code = 0): array
    {
        return static::response($message , $data , $code);
    }

    public static function error($message = '' , $data = '' , int $code = 400): array
    {
        return static::response($message , $data , $code);
    }

    public static function response($message = '' , $data = '' , int $code = 0): array
    {
        return compact('code' , 'message' , 'data');
    }
}
