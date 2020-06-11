<?php


namespace App\Customize\api\v1\action;


use App\Http\Controllers\api\v1\Base;
use Mews\Captcha\Facades\Captcha;

class MiscAction extends Action
{
    public static function captcha(Base $context , array $param = [])
    {
        $res = Captcha::create('default' , true);
        return self::success($res);
    }
}
