<?php


namespace App\Customize\api\web\model;


use Illuminate\Contracts\Pagination\Paginator;

class VideoCompanyModel extends Model
{
    protected $table = 'xq_video_company';


    public static function index($field = null , array $filter = null , array $order = null  , int $size = 20): Paginator
    {
        $field = $field ?? '*';
        $filter = $filter ?? [];
        $filter['id'] = $filter['id'] ?? '';
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['value'] = $filter['value'] ?? '';

        $order = $order ?? [];
        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'desc';

        $where = [];

        if ($filter['id'] !== '') {
            $where[] = ['id' , '=' , $filter['id']];
        }

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        $query = self::select($field)
            ->where($where);

        if ($filter['value'] !== '') {
            $query->where(function($query) use($filter){
                $query->where('name' , 'like' , "%{$filter['value']}%");
            });
        }

        return $query->orderBy($order['field'] , $order['value'])
            ->paginate($size);
    }
}
