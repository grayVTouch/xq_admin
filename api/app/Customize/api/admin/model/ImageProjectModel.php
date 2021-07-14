<?php


namespace App\Customize\api\admin\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ImageProjectModel extends Model
{
    protected $table = 'xq_image_project';

    public static function index(array $filter = [] , array $order = [] , int $size = 20): Paginator
    {
        $filter['id']           = $filter['id'] ?? '';
        $filter['name']         = $filter['name'] ?? '';
        $filter['user_id']      = $filter['user_id'] ?? '';
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['category_id']  = $filter['category_id'] ?? '';
        $filter['image_subject_id']   = $filter['image_subject_id'] ?? '';
        $filter['type']         = $filter['type'] ?? '';
        $filter['status']       = $filter['status'] ?? '';

        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'desc';

        $where = [];

        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }

        if ($filter['name'] !== '') {
            $where[] = ['name' , 'like' , "%{$filter['name']}%"];
        }

        if ($filter['user_id'] !== '') {
            $where[] = ['user_id' , '=' , $filter['user_id']];
        }

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['category_id'] !== '') {
            $where[] = ['category_id' , '=' , $filter['category_id']];
        }

        if ($filter['image_subject_id'] !== '') {
            $where[] = ['image_subject_id' , '=' , $filter['image_subject_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }

        if ($filter['status'] !== '') {
            $where[] = ['status' , '=' , $filter['status']];
        }

        return self::where($where)
            ->orderBy($order['field'] , $order['value'])
            ->paginate($size);
    }

    public static function findByName(string $name): ?ImageProjectModel
    {
        return self::where('name' , $name)->first();
    }

    public static function findByModuleIdAndName(int $module_id , string $name): ?ImageProjectModel
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['name' , '=' , $name] ,
        ])->first();
    }

    public static function findByNameAndExcludeId(string $name , int $exclude_id): ?ImageProjectModel
    {
        return self::where([
                ['name' , '=' , $name] ,
                ['id' , '!=' , $exclude_id] ,
            ])
            ->first();
    }

    public static function findByModuleIdAndNameAndExcludeId(int $module_id , string $name , int $exclude_id): ?ImageProjectModel
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['name' , '=' , $name] ,
            ['id' , '!=' , $exclude_id] ,
        ])
            ->first();
    }
}
