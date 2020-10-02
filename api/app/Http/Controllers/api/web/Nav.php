<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\NavAction;
use function api\web\error;
use function api\web\success;

class Nav extends Base
{
    public function all()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = NavAction::all($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
