<?php


namespace App\Http\Controllers\api\v1;


use App\Customize\api\v1\action\MiscAction;
use function api\v1\error;
use function api\v1\success;

class Misc extends Base
{
    // 图形验证码
    public function captcha()
    {
        $res = MiscAction::captcha();
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
