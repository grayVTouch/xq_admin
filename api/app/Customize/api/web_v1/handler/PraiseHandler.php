<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\model\PraiseModel;
use App\Customize\api\web_v1\model\UserModel;
use stdClass;
use function core\convert_obj;

class PraiseHandler extends Handler
{
    public static function handle(?PraiseModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $user = UserModel::find($res->user_id);
        UserHandler::handle($user);


        $res->module = $module;
        $res->user = $user;

        return $res;
    }

}
