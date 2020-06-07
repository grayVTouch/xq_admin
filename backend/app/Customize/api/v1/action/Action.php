<?php


namespace App\Customize\api\v1\action;


class Action
{
    public static function success($data = '' , $code = 0)
    {
        return static::response($data , $code);
    }

    public static function error($data = '' , $code = 400)
    {
        return static::response($data , $code);
    }

    public static function response($data = '' , $code = 0)
    {
        return compact('code' , 'data');
    }
}
