<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\ImageSubjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\RelationTagModel;
use App\Customize\api\web\model\VideoSubjectModel;
use App\Model\Model;
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
            case 'image_subject':
                $relation = ImageSubjectModel::find($res->relation_id);
                $relation = ImageSubjectHandler::handle($relation);
                break;
            case 'video_subject':
                $relation = VideoSubjectModel::find($res->relation_id);
                $relation = VideoSubjectHandler::handle($relation);
                break;
        }


        $res->module = $module;
        $res->relation = $relation;

        return $res;
    }

}
