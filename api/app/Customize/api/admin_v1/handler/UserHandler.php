<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\TagModel;
use App\Customize\api\admin_v1\model\UserModel;
use Illuminate\Support\Facades\Storage;
use stdClass;
use function api\admin_v1\get_value;
use function core\convert_obj;

class UserHandler extends Handler
{
    public static function handle(?UserModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        $res->__avatar__ = empty($res->avatar) ? '' : Storage::url($res->avatar);
        $res->__sex__ = get_value('business.sex_for_user' , $res->sex);
        return $res;
    }

}
