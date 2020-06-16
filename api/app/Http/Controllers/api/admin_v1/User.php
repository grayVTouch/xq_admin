<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\UserAction;
use App\Customize\api\admin_v1\model\AdminPermissionModel;
use Core\Lib\Category;
use function api\admin_v1\error;
use function api\admin_v1\success;
use function api\admin_v1\user;
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

    public function search()
    {
        $value = $this->request->get('value' , '');
        $res = UserAction::search($this , $value);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
