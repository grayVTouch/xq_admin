<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\VideoSubjectModel;
use Illuminate\Support\Facades\Storage;
use stdClass;
use function core\convert_obj;

class VideoSubjectHandler extends Handler
{
    public static function handle(?VideoSubjectModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $res->module = $module;
        $res->__attr__ = empty($res->attr) ? [] : json_decode($res->attr , true);
        $res->__thumb__ = empty($res->thumb) ? '' : Storage::url($res->thumb);
        return $res;
    }

}
