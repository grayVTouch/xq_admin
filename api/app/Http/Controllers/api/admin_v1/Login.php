<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\LoginAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class Login extends Base
{
    public function login()
    {
        $param = $this->request->post();
        $param['username']      = $param['username'] ?? '';
        $param['password']      = $param['password'] ?? '';
        $param['captcha_key']    = $param['captcha_key'] ?? '';
        $param['captcha_code']   = $param['captcha_code'] ?? '';
        $res = LoginAction::login($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function avatar()
    {
        $param = $this->request->query();
        $param['username'] = $param['username'] ?? '';
        $res = LoginAction::avatar($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
