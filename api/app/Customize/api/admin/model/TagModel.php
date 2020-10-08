<?php


namespace App\Customize\api\admin\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TagModel extends Model
{
    protected $table = 'xq_tag';

    public static function index(array $filter = [] , array $order = [] , int $limit = 20): Paginator
    {
        $filter['id']           = $filter['id'] ?? '';
        $filter['name']         = $filter['name'] ?? '';
        $filter['module_id']    = $filter['module_id'] ?? '';

        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'asc';

        $where = [];

        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['name'] !== '') {
            $where[] = ['name' , 'like' , "%{$filter['name']}%"];
        }

        return self::where($where)
            ->orderBy($order['field'] , $order['value'])
            ->paginate($limit);
    }

    public static function search(string $value = ''): Collection
    {
        return self::where('id' , $value)
            ->orWhere('name' , $value)
            ->get();
    }

    public static function topByModuleId(int $module_id = 0 , int $limit = 10): Collection
    {
        return self::where('module_id' , $module_id)
            ->orderBy('count' , 'desc')
            ->limit($limit)
            ->get();
    }

    public static function findByModuleIdAndName(int $module_id , string $name = ''): ?TagModel
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['name' , '=' , $name] ,
        ])->first();
    }

    public static function findByExcludeIdAndModuleIdAndName(int $exclude_id , int $module_id , string $name = ''): ?TagModel
    {
        return self::where([
            ['id' , '!=' , $exclude_id] ,
            ['module_id' , '=' , $module_id] ,
            ['name' , '=' , $name] ,
        ])->first();
    }
}
