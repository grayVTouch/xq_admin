<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\ImageProjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\RelationTagModel;
use App\Customize\api\web\model\VideoProjectModel;
use App\Customize\api\web\model\Model;
use stdClass;
use function core\convert_object;

class RelationTagHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        switch ($res->relation_type)
        {
            case 'image_project':
                $relation = ImageProjectModel::find($res->relation_id);
                $relation = ImageProjectHandler::handle($relation);
                break;
            case 'video_project':
                $relation = VideoProjectModel::find($res->relation_id);
                $relation = VideoProjectHandler::handle($relation);
                break;
        }


        $res->module = $module;
        $res->relation = $relation;

        return $res;
    }

}
