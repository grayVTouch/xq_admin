<?php


namespace App\Customize\api\web\util;


use App\Customize\api\web\model\ResourceModel;
use function core\current_datetime;

class ResourceUtil
{
    /**
     * 穿件资源
     *
     * @param string $url
     * @param string $path
     * @param string $disk
     * @param int $is_used
     * @param int $is_deleted
     * @return int
     */
    public static function create(string $url , string $path , string $disk , int $is_used = 0 , int $is_deleted = 0): int
    {
        if (empty($path)) {
            return -1;
        }
        $datetime = current_datetime();
        return \App\Customize\api\admin\model\ResourceModel::insertGetId([
            'url'           => $url ,
            'path'          => $path ,
            'disk'          => $disk ,
            'is_used'       => $is_used ,
            'is_deleted'    => $is_deleted ,
            'created_at'    => $datetime ,
        ]);
    }

    public static function delete(string $path): ?int
    {
        if (empty($path)) {
            return null;
        }
        return ResourceModel::updateByUrl($path , [
            'is_deleted' => 1 ,
        ]);
    }

    public static function used(string $path): ?int
    {
        if (empty($path)) {
            return null;
        }
        return ResourceModel::updateByUrl($path , [
            'is_used' => 1 ,
        ]);
    }
}
