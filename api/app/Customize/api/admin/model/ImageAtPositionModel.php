<?php


namespace App\Customize\api\admin\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ImageAtPositionModel extends Model
{
    protected $table = 'xq_image_at_position';

    public static function index(array $filter = [] , array $order = [] , int $limit = 20): Paginator
    {
        $filter['id'] = $filter['id'] ?? '';
        $filter['name'] = $filter['name'] ?? '';
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['platform'] = $filter['platform'] ?? '';
        $filter['position_id'] = $filter['position_id'] ?? '';
        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'asc';
        $where = [];
        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }
        if ($filter['name'] !== '') {
            $where[] = ['name' , 'like' , "%{$filter['name']}%"];
        }
        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }
        if ($filter['platform'] !== '') {
            $where[] = ['platform' , '=' , $filter['platform']];
        }
        if ($filter['position_id'] !== '') {
            $where[] = ['position_id' , '=' , $filter['position_id']];
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

    public static function top(int $limit = 10): Collection
    {
        return self::orderBy('count' , 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getByName(string $name = ''): ?TagModel
    {
        return self::where('name' , $name)->first();
    }
}