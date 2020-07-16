<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\NavAction;
use function api\web_v1\error;
use function api\web_v1\success;

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
