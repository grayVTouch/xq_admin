<?php


namespace App\Customize\api\admin\model;


use Illuminate\Database\Eloquent\Collection;

class CategoryModel extends Model
{
    protected $table = 'xq_category';

    public static function getAll()
    {
        return self::orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

    public static function getByModuleId(int $module_id): Collection
    {
        return self::where('module_id' , $module_id)
            ->orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

    public static function getByFilter(array $filter = []): Collection
    {
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['type']         = $filter['type'] ?? '';

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }

        return self::where($where)
            ->orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

    public static function index(array $field = null , array $filter = null , array $order = null): Collection
    {
        $field = $field ?? 'g.*';
        $filter = $filter ?? [];
        $order = $order ?? ['field' => 'id' , 'value' => 'desc'];
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['type']         = $filter['type'] ?? '';

        $where = [];
        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }
        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }
        return self::where($where)->get();
    }
}
