<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class UserModel extends Model
{
    protected $table = 'xq_user';

    public static function search(string $value = ''): Collection
    {
        return self::where('id' , $value)
            ->orWhere('username' , $value)
            ->orWhere('phone' , $value)
            ->orWhere('email' , $value)
            ->get();
    }

    public static function index(array $filter = [] , array $order = [] , int $limit = 20): Paginator
    {
        $filter['id'] = $filter['id'] ?? '';
        $filter['username'] = $filter['username'] ?? '';
        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'asc';
        $where = [];
        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }
        if ($filter['username'] !== '') {
            $where[] = ['username' , 'like' , "%{$filter['username']}%"];
        }
        return self::where($where)
            ->orderBy($order['field'] , $order['value'])
            ->paginate($limit);
    }

    public static function findByUsername(string $username = ''): ?UserModel
    {
        return self::where('username' , $username)->first();
    }

}
