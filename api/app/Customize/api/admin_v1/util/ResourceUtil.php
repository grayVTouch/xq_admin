<?php


namespace App\Customize\api\admin_v1\util;


use App\Customize\api\admin_v1\model\ResourceModel;
use function core\current_time;

class ResourceUtil
{
    public static function create(string $path = '' , int $used = 0 , int $is_delete = 0): ?int
    {
        if (empty($path)) {
            return null;
        }
        $datetime = current_time();
        return ResourceModel::insertGetId([
            'path'          => $path ,
            'is_delete'     => $used ,
            'used'          => $is_delete ,
            'create_time'   => $datetime ,
        ]);
    }

    public static function delete(string $path): ?int
    {
        if (empty($path)) {
            return null;
        }
        return ResourceModel::updateByPath($path , [
            'is_delete' => 1 ,
        ]);
    }

    public static function used(string $path): ?int
    {
        if (empty($path)) {
            return null;
        }
        return ResourceModel::updateByPath($path , [
            'used' => 1 ,
        ]);
    }
}
