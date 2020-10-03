<?php


namespace App\Customize\api\web\util;


use App\Customize\api\web\model\ResourceModel;
use function core\current_datetime;

class ResourceUtil
{
    public static function create(string $path = '' , int $used = 0 , int $is_delete = 0): ?int
    {
        if (empty($path)) {
            return null;
        }
        $datetime = current_datetime();
        return ResourceModel::insertGetId([
            'path'          => $path ,
            'is_delete'     => $used ,
            'used'          => $is_delete ,
            'created_at'   => $datetime ,
        ]);
    }

    public static function delete(string $path): ?int
    {
        if (empty($path)) {
            return null;
        }
        return ResourceModel::updateByUrl($path , [
            'is_delete' => 1 ,
        ]);
    }

    public static function used(string $path): ?int
    {
        if (empty($path)) {
            return null;
        }
        return ResourceModel::updateByUrl($path , [
            'used' => 1 ,
        ]);
    }
}
