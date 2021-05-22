<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\ImageSubjectModel;
use App\Customize\api\web\util\FileUtil;
use App\Customize\api\web\model\Model;
use stdClass;
use function core\convert_object;

class ImageSubjectHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);

        $model->__attr__ = empty($model->attr) ? [] : json_decode($model->attr , true);

        if (in_array('module' , $with)) {
            $module = ModuleModel::find($model->module_id);
            $module = ModuleHandler::handle($module);
            $model->module = $module;
        }

        return $model;
    }



}
