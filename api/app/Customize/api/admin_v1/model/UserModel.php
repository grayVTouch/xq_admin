<?php


namespace App\Customize\api\admin_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class UserModel extends Model
{
    protected $table = 'xq_user';

    public static function search(string $value = '' , int $limit = 20): Paginator
    {
        return self::where('id' , $value)
            ->orWhere('username' , 'like' , "%{$value}%")
            ->orWhere('phone' , 'like' ,  "%{$value}%")
            ->orWhere('email' , 'like' , "%{$value}%")
            ->paginate($limit);
    }

    public static function index(array $filter = [] , array $order = [] , int $limit = 20): Paginator
    {
        $filter['id']       = $filter['id'] ?? '';
        $filter['username'] = $filter['username'] ?? '';
        $filter['nickname'] = $filter['nickname'] ?? '';
        $filter['sex']      = $filter['sex'] ?? '';
        $filter['phone']    = $filter['phone'] ?? '';
        $filter['email']    = $filter['email'] ?? '';
        $order['field']     = $order['field'] ?? 'id';
        $order['value']     = $order['value'] ?? 'asc';

        $where = [];

        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }

        if ($filter['username'] !== '') {
            $where[] = ['username' , 'like' , "%{$filter['username']}%"];
        }

        if ($filter['nickname'] !== '') {
            $where[] = ['nickname' , 'like' , "%{$filter['nickname']}%"];
        }

        if ($filter['sex'] !== '') {
            $where[] = ['sex' , '=' , $filter['sex']];
        }

        if ($filter['phone'] !== '') {
            $where[] = ['phone' , 'like' , "%{$filter['phone']}%"];
        }

        if ($filter['email'] !== '') {
            $where[] = ['email' , 'like' , "%{$filter['email']}%"];
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
