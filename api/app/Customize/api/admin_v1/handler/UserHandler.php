<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\TagModel;
use App\Customize\api\admin_v1\model\UserModel;
use stdClass;
use function core\convert_obj;

class UserHandler extends Handler
{
    public static function handle(?UserModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        $res->__avatar__ = empty($res->avatar) ? '' : asset($res->avatar);
        return $res;
    }

}
