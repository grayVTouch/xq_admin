<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2019/11/17
 * Time: 16:07
 */

namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\PannelAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class Pannel extends Base
{
    // 统计信息
    public function info()
    {
        $res = PannelAction::info($this);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
