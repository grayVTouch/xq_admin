<?php


namespace App\Customize\api\web_v1\action;


class Action
{
    public static function success($data = '' , int $code = 0): array
    {
        return static::response($data , $code);
    }

    public static function error($data = '' , int $code = 400): array
    {
        return static::response($data , $code);
    }

    public static function response($data = '' , int $code = 0): array
    {
        return compact('code' , 'data');
    }
}
