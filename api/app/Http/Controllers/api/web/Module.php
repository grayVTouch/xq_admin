<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\ModuleAction;
use function api\web\error;
use function api\web\success;

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

    public function default()
    {
        $res = ModuleAction::default($this);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

}
