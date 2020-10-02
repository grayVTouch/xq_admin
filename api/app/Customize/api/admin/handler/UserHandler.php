<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\TagModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\util\FileUtil;
use stdClass;
use function api\admin\get_value;
use function core\convert_obj;

class UserHandler extends Handler
{
    public static function handle(?UserModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $res->__sex__ = get_value('business.sex_for_user' , $res->sex);
        return $res;
    }

}
