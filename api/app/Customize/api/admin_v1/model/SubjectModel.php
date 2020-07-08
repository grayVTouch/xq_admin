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
        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'asc';
        $where = [];
        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }
        if ($filter['name'] !== '') {
            $where[] = ['name' , 'like' , "%{$filter['name']}%"];
        }
        return self::where($where)
            ->orderBy($order['field'] , $order['value'])
            ->paginate($limit);
    }

    public static function search($module_id , $value , int $limit = 20): Collection
    {
        return self::where('module_id' , $module_id)
            ->where(function($query) use($value){
                $query->where('id' , $value)
                    ->orWhere('name' , 'like' , "%{$value}%");
            })
            ->orderBy('weight' , 'desc')
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }
}
