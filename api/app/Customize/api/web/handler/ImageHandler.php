<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\CollectionModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\PraiseModel;
use App\Customize\api\web\model\UserModel;
use App\Customize\api\web\model\Model;
use stdClass;
use function api\web\user;
use function core\convert_object;

class ImageHandler extends Handler
{
    public static function handle(?Model $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        return $res;
    }

    // 附加：是否收藏
    public static function isCollected($model): void
    {
        if (empty($model)) {
            return ;
        }
        if (empty(user())) {
            $model->is_collected = 0;
        } else {
            $model->is_collected = CollectionModel::findByModuleIdAndUserIdAndRelationTypeAndRelationId($model->module_id , user()->id , 'image' , $model->id) ? 1 : 0;
        }
    }

    // 附加：是否点赞
    public static function isPraised($model): void
    {
        if (empty($model)) {
            return ;
        }
        if (empty(user())) {
            $model->is_praised = 0;
        } else {
            $model->is_praised = PraiseModel::findByModuleIdAndUserIdAndRelationTypeAndRelationId($model->module_id , user()->id , 'image' , $model->id) ? 1 : 0;
        }
    }

    // 附加：模块
    public static function module($model): void
    {
        if (empty($model)) {
            return ;
        }
        $module = ModuleModel::find($model->module_id);
        $module = ModuleHandler::handle($module);

        $model->module = $module;
    }

    // 附加：用户
    public static function user($model): void
    {
        if (empty($model)) {
            return ;
        }
        $user = UserModel::find($model->user_id);
        $user = UserHandler::handle($user);

        $model->user = $user;
    }
}
