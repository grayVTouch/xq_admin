<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\DiskModel;
use App\Customize\api\admin\util\FileUtil;
use stdClass;
use function api\admin\get_value;
use function core\convert_obj;
use function core\format_capacity;
use function core\obj_to_array;

class DiskHandler extends Handler
{
    public static function handle(?DiskModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_obj($model);
        $total_size = format_capacity(disk_total_space($model->path) , 2);
        $free_size = format_capacity(disk_free_space($model->path) , 2);

        $model->total_size = $total_size;
        $model->free_size = $free_size;

        return $model;
    }
}
