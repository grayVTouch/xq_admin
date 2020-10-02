<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\LoginAction;
use function api\admin\error;
use function api\admin\success;

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
