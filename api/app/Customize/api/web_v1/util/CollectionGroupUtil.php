<?php


namespace App\Customize\api\web_v1\util;


use App\Customize\api\web_v1\model\CollectionModel;
use stdClass;

class CollectionGroupUtil
{
    public static function handle(stdClass $collection_group , string $relation_type = '' , int $relation_id = 0)
    {
        if (!empty($relation_type) && !empty($relation_id)) {
            $collection_group->inside = CollectionModel::findByModuleIdAndUserIdAndCollectionGroupIdAndRelationTypeAndRelationId($collection_group->module_id , $collection_group->user_id , $collection_group->id , $relation_type , $relation_id) ? 1 : 0;
        }
    }
}
