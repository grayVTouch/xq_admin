<?php


namespace App\Customize\api\admin\action;


use App\Http\Controllers\api\admin\Base;
use Mews\Captcha\Facades\Captcha;

class MiscAction extends Action
{
    public static function captcha(Base $context , array $param = [])
    {
        $res = Captcha::create('default' , true);
        return self::success('' , $res);
    }
}
