<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\PraiseModel;
use App\Customize\api\web\model\UserModel;
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
