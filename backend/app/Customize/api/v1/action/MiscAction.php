<?php


namespace App\Customize\api\v1\action;


use Mews\Captcha\Facades\Captcha;

class MiscAction extends Action
{
    public static function captcha()
    {
        $res = Captcha::create('default' , true);
        return self::success($res);
    }
}
