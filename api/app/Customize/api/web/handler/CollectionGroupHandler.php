<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\CollectionGroupModel;
use App\Customize\api\web\model\CollectionModel;
use App\Customize\api\web\model\ImageProjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\UserModel;
use App\Customize\api\web\model\Model;
use stdClass;
use function api\web\user;
use function core\convert_object;

class CollectionGroupHandler extends Handler
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

        // 该收藏夹内的物品数量
        $res->count = CollectionModel::countByCollectionGroupId($res->id);
//        $res->count = CollectionModel::countByModuleIdAndUserIdAndCollectionGroupId($res->module_id , $res->user_id , $res->id);
        $res->count_for_image_project = CollectionModel::countByCollectionGroupIdAndRelationType($res->id , 'image_project');

        $res->module = $module;
        $res->user = $user;

        // 收藏夹封面
        $thumb = '';
        $collection = CollectionModel::firstOrderIdAscByCollectionGroupId($res->id);
        if (!empty($collection)) {
            switch ($collection->relation_type)
            {
                case 'image_project':
                    $relation = ImageProjectModel::find($collection->relation_id);
                    $relation = ImageProjectHandler::handle($relation);
                    $thumb = $relation->thumb;
                    break;
            }
        }

        $res->thumb = $thumb;
        return $res;
    }


}
