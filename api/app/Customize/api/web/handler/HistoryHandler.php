<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\HistoryModel;
use App\Customize\api\web\model\ImageProjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\UserModel;
use App\Customize\api\web\model\Model;
use stdClass;
use function core\convert_object;

class HistoryHandler extends Handler
{
    public static function handle(?Model $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        return $res;
    }

    public static function module($model): void
    {
        if (empty($model)) {
            return ;
        }
        $module = ModuleModel::find($model->module_id);
        ModuleHandler::handle($module);
        $model->module = $module;
    }

    public static function user($model): void
    {
        if (empty($model)) {
            return ;
        }
        $user = UserModel::find($model->user_id);
        UserHandler::handle($user);
        $model->user = $user;
    }

    public static function relation($model): void
    {
        if (empty($model)) {
            return ;
        }
        // 关联的主题
        switch ($model->relation_type)
        {
            case 'image_project':
                $relation = ImageProjectModel::find($model->relation_id);
                $relation = ImageProjectHandler::handle($relation);
                break;
            default:
                $relation = null;
        }
        $model->relation = $relation;
    }


}
