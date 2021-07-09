<?php


namespace App\Customize\api\web\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ImageSubjectModel extends Model
{
    protected $table = 'xq_image_subject';

    public static function index(array $filter = [] , array $order = [] , int $size = 20): Paginator
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
            ->paginate($size);
    }

    public static function search(string $value = ''): Collection
    {
        return self::where('id' , $value)
            ->orWhere('name' , $value)
            ->get();
    }

    public static function getWithPagerInImageProjectByModuleIdAndValueAndLimit(int $module_id , string $value = '' , int $size = 20)
    {
        return self::from('xq_image_subject as is')
            ->where('name' , 'like' , "%{$value}%")
            ->whereExists(function($query) use($module_id){
                $query->select('id')
                    ->from('xq_image_project')
                    ->where([
                        ['module_id' , '=' , $module_id] ,
                        ['type' , '=' , 'pro'] ,
                        ['status' , '=' , 1] ,
                    ])
                    ->whereRaw('is.id = image_subject_id');
            })
            ->paginate($size);
    }
}
