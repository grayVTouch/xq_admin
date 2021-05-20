<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\DiskHandler;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\DiskModel;
use App\Http\Controllers\api\admin\Base;
use Core\Lib\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function api\admin\my_config_keys;
use function api\admin\parse_order;
use function core\array_unit;
use function core\current_datetime;

class SystemDiskAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $res = [];
        if (empty($param['parent_path'])) {
            $fastjson_jar_path  = __DIR__ . "/../java/jar/fastjson.jar";
            $out_path           = __DIR__ . "/../java/out";
            $command = "java -cp \"{$fastjson_jar_path};{$out_path}\" App";
            // 列出盘符
            exec($command , $res , $status);
            if ($status > 0) {
                return self::error("获取盘符失败【status: {$status}】" , $res);
            }
            $res = implode('' , $res);
            $res = json_decode($res , true);
        } else {
            // 列出子级
            $res = File::getDirs($param['parent_path'] , false);
        }
        return self::success('' , $res);
    }
}
