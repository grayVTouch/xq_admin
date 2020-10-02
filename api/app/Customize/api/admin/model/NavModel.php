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

}
