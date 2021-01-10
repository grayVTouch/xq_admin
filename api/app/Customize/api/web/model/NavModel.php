<?php


namespace App\Customize\api\web\model;


use Illuminate\Database\Eloquent\Collection;

class NavModel extends Model
{
    protected $table = 'xq_nav';

    public static function getAllByModuleId(int $module_id): Collection
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['is_enabled' , '=' , 1] ,
            ])
            ->orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

}
