<?php


namespace App\Customize\api\web\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TagModel extends Model
{
    protected $table = 'xq_tag';

    public static function index(array $filter = [] , array $order = [] , int $size = 20): Paginator
    {
        $filter['id'] = $filter['id'] ?? '';
        $filter['name'] = $filter['name'] ?? '';
        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'desc';
        $where = [];
        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }
        if ($filter['name'] !== '') {
            $where[] = ['name' , 'like' , "%{$filter['name']}%"];
        }
        return self::where($where)
            ->orderBy($order['field'] , $order['value'])
            ->paginate($size);
    }

    public static function search(string $value = ''): Collection
    {
        return self::where('id' , $value)
            ->orWhere('name' , $value)
            ->get();
    }

    public static function top(int $size = 10): Collection
    {
        return self::orderBy('count' , 'desc')
            ->limit($size)
            ->get();
    }

    public static function getByName(string $name = ''): ?TagModel
    {
        return self::where('name' , $name)->first();
    }
}
