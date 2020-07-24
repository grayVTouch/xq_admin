<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\CollectionGroupModel;
use App\Customize\api\web_v1\model\CollectionModel;
use App\Customize\api\web_v1\model\ImageSubjectModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\model\UserModel;
use stdClass;
use function api\web_v1\user;
use function core\convert_obj;

class CollectionGroupHandler extends Handler
{
    public static function handle(?CollectionGroupModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $user = UserModel::find($res->user_id);
        UserHandler::handle($user);

        // 该收藏夹内的物品数量
        $res->count = CollectionModel::countByCollectionGroupId($res->id);
//        $res->count = CollectionModel::countByModuleIdAndUserIdAndCollectionGroupId($res->module_id , $res->user_id , $res->id);
        $res->count_for_image_subject = CollectionModel::countByCollectionGroupIdAndRelationType($res->id , 'image_subject');

        $res->module = $module;
        $res->user = $user;

        // 收藏夹封面
        $thumb = '';
        $collection = CollectionModel::firstOrderIdAscByCollectionGroupId($res->id);
        if (!empty($collection)) {
            switch ($collection->relation_type)
            {
                case 'image_subject':
                    $relation = ImageSubjectModel::find($collection->relation_id);
                    $relation = ImageSubjectHandler::handle($relation);
                    $thumb = $relation->__thumb__;
                    break;
            }
        }

        $res->thumb = $thumb;
        return $res;
    }


}
