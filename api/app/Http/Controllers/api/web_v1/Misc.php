<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\MiscAction;
use function api\web_v1\error;
use function api\web_v1\success;

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

    // 发送修改密码的邮箱验证码
    public function sendEmailCodeForPassword()
    {
        $param = $this->request->post();
        $param['email'] = $param['email'] ?? '';
        $res = MiscAction::sendEmailCode($this , 'password' , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    // 发送注册的邮箱验证码
    public function sendEmailCodeForRegister()
    {
        $param = $this->request->post();
        $param['email'] = $param['email'] ?? '';
        $res = MiscAction::sendEmailCode($this , 'register' , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
