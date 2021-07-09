<?php


namespace App\Customize\api\admin\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RegionModel extends Model
{
    protected $table = 'xq_region';

    public static function country(): Collection
    {
        return self::where('type' , 'country')
            ->get();
    }

    public static function index(array $filter = [] , int $size = 20): Paginator
    {
        $filter['value'] = $filter['value'] ?? '';
        $filter['type'] = $filter['type'] ?? '';

        $where = [];
        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }
        return self::where($where)
            ->where(function($query) use($filter){
                $query->where('id' , $filter['value'])
                    ->orWhere('name' , 'like' , "%{$filter['value']}%");
            })
            ->orderBy('id' , 'asc')
            ->paginate($size);
    }
}
