<?php


namespace App\Customize\api\admin\repository;


use App\Customize\api\admin\model\ResourceModel;
use function core\current_datetime;

class ResourceRepository
{
    /**
     * 穿件资源
     *
     * @param string $url
     * @param string $path
     * @param string $disk
     * @param int $is_used
     * @param int $is_deleted
     * @return void
     */
    public static function create(string $url , string $path , string $disk , int $is_used = 0 , int $is_deleted = 0): void
    {
        if (empty($path)) {
            return ;
        }
        $datetime = current_datetime();
        $res = null;
        if (empty($url)) {
            $res = ResourceModel::findByUrlOrPath($path);
        } else {
            $res = ResourceModel::findByUrlOrPath($url);
        }
        if (empty($res)) {
            ResourceModel::insert([
                'url'           => $url ,
                'path'          => $path ,
                'disk'          => $disk ,
                'is_used'       => $is_used ,
                'is_deleted'    => $is_deleted ,
                'created_at'    => $datetime ,
            ]);
            return ;
        }
        ResourceModel::updateByUrlOrPath($url , [
            'path'          => $path ,
            'disk'          => $disk ,
            'is_used'       => $is_used ,
            'is_deleted'    => $is_deleted ,
            'updated_at'    => $datetime ,
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
    public static function delete(string $value): int
    {
        if (empty($value)) {
            return -1;
        }
        return ResourceModel::updateByUrlOrPath($value , [
            'is_deleted' => 1 ,
        ]);
    }

    public static function used(string $value): int
    {
        if (empty($value)) {
            return -1;
        }
        return ResourceModel::updateByUrlOrPath($value , [
            'is_used' => 1 ,
        ]);
    }
}
