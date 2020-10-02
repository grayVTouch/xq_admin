<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\util\FileUtil;
use stdClass;
use function core\convert_obj;

class VideoSrcHandler extends Handler
{
    public static function handle(?VideoSrcModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        return $res;
    }

}
