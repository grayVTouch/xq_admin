<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\HistoryModel;
use App\Customize\api\web\model\ImageSubjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\UserModel;
use stdClass;
use function core\convert_obj;

class HistoryHandler extends Handler
{
    public static function handle(?HistoryModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $user = UserModel::find($res->user_id);
        UserHandler::handle($user);

        // 关联的主题
        switch ($res->relation_type)
        {
            case 'image_subject':
                $relation = ImageSubjectModel::find($res->relation_id);
                $relation = ImageSubjectHandler::handle($relation);
                break;
            default:
                $relation = null;
        }

        $res->module = $module;
        $res->user = $user;
        $res->relation = $relation;

        return $res;
    }

}
