<?php


namespace App\Customize\api\admin\util;


use App\Customize\api\admin\model\ResourceModel;
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
        return ResourceModel::insertGetId([
            'url'           => $url ,
            'path'          => $path ,
            'disk'          => $disk ,
            'is_used'       => $is_used ,
            'is_deleted'    => $is_deleted ,
            'created_at'    => $datetime ,
        ]);
    }

    /**
     * 删除资源
     *
     * @author running
     *
     * @param string $path
     * @return int|null
     */
    public static function delete(string $url): int
    {
        if (empty($path)) {
            return -1;
        }
        return ResourceModel::updateByUrl($url , [
            'is_deleted' => 1 ,
        ]);
    }

    public static function used(string $url): int
    {
        if (empty($path)) {
            return -1;
        }
        return ResourceModel::updateByUrl($url , [
            'is_used' => 1 ,
        ]);
    }
}
