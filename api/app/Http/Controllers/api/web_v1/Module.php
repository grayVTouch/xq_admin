<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ModuleAction;
use function api\web_v1\error;
use function api\web_v1\success;

class Module extends Base
{
    public function all()
    {
        $res = ModuleAction::all($this);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
