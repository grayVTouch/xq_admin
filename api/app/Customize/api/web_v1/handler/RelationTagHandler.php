<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\ImageSubjectModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\model\RelationTagModel;
use App\Customize\api\web_v1\model\VideoSubjectModel;
use stdClass;
use function core\convert_obj;

class RelationTagHandler extends Handler
{
    public static function handle(?RelationTagModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

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
