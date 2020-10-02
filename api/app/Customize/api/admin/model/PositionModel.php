<?php


namespace App\Customize\api\admin\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class PositionModel extends Model
{
    protected $table = 'xq_position';

    public static function index(array $filter = [] , array $order = [] , int $limit = 20): Paginator
    {
        $filter['id']       = $filter['id'] ?? '';
        $filter['value']    = $filter['value'] ?? '';
        $filter['platform'] = $filter['platform'] ?? '';

        $order['field']     = $order['field'] ?? 'id';
        $order['value']     = $order['value'] ?? 'asc';

        $where = [];

        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }

        if ($filter['value'] !== '') {
            $where[] = ['value' , 'like' , "%{$filter['value']}%"];
        }

        if ($filter['platform'] !== '') {
            $where[] = ['platform' , '=' , $filter['platform']];
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

    public static function getByPlatformAndValue(string $platform , string $value = ''): ?PositionModel
    {
        return self::where([
                    ['platform' , '=' , $platform] ,
                    ['value' , '=' , $value] ,
                ])
                ->first();
    }
}
