<?php


namespace App\Http\Controllers\api\v1;


use App\Customize\api\v1\action\UserAction;
use App\Customize\api\v1\model\AdminPermissionModel;
use Core\Lib\Category;
use function api\v1\error;
use function api\v1\success;
use function api\v1\user;
use function core\obj_to_array;

class User extends Base
{
    public function info()
    {
        $res = UserAction::info($this , []);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
