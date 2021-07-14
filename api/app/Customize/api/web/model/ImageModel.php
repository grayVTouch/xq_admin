<?php


namespace App\Customize\api\web\model;


use Illuminate\Database\Eloquent\Collection;

class ImageModel extends Model
{
    protected $table = 'xq_image';

    public static function getByImageProjectId(int $image_subject_id): Collection
    {
        return self::where('image_project_id' , $image_subject_id)->get();
    }

    public static function getNewestByFilterAndSize(array $filter = [] , int $size = 0): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['ip.module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['ip.type' , '=' , $filter['type']];
        }

        return self::from('xq_image as i')
            ->leftJoin('xq_image_project as ip' , 'i.image_project_id' , '=' , 'ip.id')
            ->where($where)
            ->orderBy('ip.created_at' , 'desc')
            ->orderBy('ip.id' , 'asc')
            ->limit($size)
            ->get();
    }
}
