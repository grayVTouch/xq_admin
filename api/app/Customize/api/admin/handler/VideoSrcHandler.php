<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\model\Model;
use stdClass;
use function core\convert_object;

class VideoSrcHandler extends Handler
{
    public static function handle(?Model $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        return $res;
    }

}
