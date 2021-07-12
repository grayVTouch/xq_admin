<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\JobAction;
use function api\admin\error;
use function api\admin\success;

class Job extends Base
{
    public function retry()
    {
        $res = JobAction::retry($this);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function flush()
    {
        $res = JobAction::flush($this);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function resourceClear()
    {
        $res = JobAction::resourceClear($this);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }
}
