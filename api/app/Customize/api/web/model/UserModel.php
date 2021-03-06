<?php


namespace App\Customize\api\web\model;


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

    public static function index(array $filter = [] , array $order = [] , int $size = 20): Paginator
    {
        $filter['id'] = $filter['id'] ?? '';
        $filter['username'] = $filter['username'] ?? '';
        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'desc';
        $where = [];
        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }
        if ($filter['username'] !== '') {
            $where[] = ['username' , 'like' , "%{$filter['username']}%"];
        }
        return self::where($where)
            ->orderBy($order['field'] , $order['value'])
            ->paginate($size);
    }

    public static function findByUsername(string $username = ''): ?UserModel
    {
        return self::where('username' , $username)->first();
    }

    public static function findByValueInUsernameOrEmailOrPhone(string $value = '')
    {
        return self::where('username' , $value)
            ->orWhere('email' , $value)
            ->orWhere('phone' , $value)
            ->first();
    }



    public static function findByEmail(string $email = ''): ?UserModel
    {
        return self::where('email' , $email)->first();
    }



}
