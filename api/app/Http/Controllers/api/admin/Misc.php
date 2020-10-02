<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\MiscAction;
use function api\admin\error;
use function api\admin\success;

class Misc extends Base
{
    // 图形验证码
    public function captcha()
    {
        $res = MiscAction::captcha($this);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
