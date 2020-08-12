<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\DiskModel;
use stdClass;
use function core\convert_obj;
use function core\format_capacity;

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
