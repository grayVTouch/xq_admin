<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\JobAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

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
}
