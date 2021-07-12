<?php


namespace App\Customize\api\admin\model;


use Illuminate\Database\Eloquent\Collection;

class NavModel extends Model
{
    protected $table = 'xq_nav';

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


    public static function search(?array $filter , array $field = null , array $order = []): Collection
    {
        $filter = $filter ?? [];
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type'] = $filter['type'] ?? '';

        $field = $field ?? '*';

        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'desc';

        $where = [];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['module_id'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }

        return self::select($field)
            ->where($where)
            ->orderBy($order['field'] , $order['value'])
            ->get();
    }

}
