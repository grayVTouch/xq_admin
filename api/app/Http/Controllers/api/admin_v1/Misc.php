<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\MiscAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class Misc extends Base
{
    // 图形验证码
    public function captcha()
    {
        $res = MiscAction::captcha($this);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
