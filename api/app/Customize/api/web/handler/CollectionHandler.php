<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\CollectionGroupModel;
use App\Customize\api\web\model\CollectionModel;
use App\Customize\api\web\model\ImageProjectModel;
use App\Customize\api\web\model\UserModel;
use App\Customize\api\web\util\CollectionGroupUtil;
use App\Customize\api\web\model\Model;
use stdClass;
use function core\convert_object;

class CollectionHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);

        $user = UserModel::find($model->user_id);
        $user = UserHandler::handle($user);

        $collection_group = CollectionGroupModel::find($model->collection_group_id);
        $collection_group = CollectionGroupHandler::handle($collection_group);
        CollectionGroupUtil::handle($collection_group , $model->relation_type , $model->relation_id);

        switch ($model->relation_type)
        {
            case 'image_project':
                $relation = ImageProjectModel::find($model->relation_id);
                $relation = ImageProjectHandler::handle($relation);
                break;
            default:
                $relation = null;
        }

        $model->user = $user;
        $model->collection_group = $collection_group;
        $model->relation = $relation;

        return $model;
    }
}
