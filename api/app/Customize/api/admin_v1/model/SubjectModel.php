<?php


namespace App\Customize\api\admin_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SubjectModel extends Model
{
    protected $table = 'xq_subject';

    public static function index(array $filter = [] , array $order = [] , int $limit = 20): Paginator
    {
        $filter['id'] = $filter['id'] ?? '';
        $filter['name'] = $filter['name'] ?? '';
        $filter['module_id'] = $filter['module_id'] ?? '';
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
        return self::where($where)
            ->orderBy($order['field'] , $order['value'])
            ->paginate($limit);
    }

    public static function search(int $module_id , string $value = '' , int $limit = 20): Paginator
    {
        return self::where('module_id' , $module_id)
            ->where(function($query) use($value){
                $query->where('id' , $value)
                    ->orWhere('name' , 'like' , "%{$value}%");
            })
            ->orderBy('weight' , 'desc')
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }
}
