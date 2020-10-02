<?php


namespace App\Customize\api\web\model;


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

    public static function search(string $value = ''): Collection
    {
        return self::where('id' , $value)
            ->orWhere('name' , $value)
            ->get();
    }

    public static function getWithPagerInImageSubjectByModuleIdAndValueAndLimit(int $module_id , string $value = '' , int $limit = 20)
    {
        $value = strtolower($value);
        return self::from('xq_subject as s')
            ->whereRaw("lower(s.name) like concat('%' , '{$value}' , '%')")
            ->whereExists(function($query) use($module_id){
                $query->select('id')
                    ->from('xq_image_subject')
                    ->where([
                        ['module_id' , '=' , $module_id] ,
                        ['type' , '=' , 'pro'] ,
                        ['status' , '=' , 1] ,
                    ])
                    ->whereRaw('s.id = subject_id');
            })
            ->paginate($limit);
    }
}
