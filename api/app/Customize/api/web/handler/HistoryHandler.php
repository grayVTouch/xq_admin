<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\HistoryModel;
use App\Customize\api\web\model\ImageSubjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\UserModel;
use App\Model\Model;
use stdClass;
use function core\convert_object;

class HistoryHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

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
