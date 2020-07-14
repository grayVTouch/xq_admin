<?php


namespace App\Customize\api\admin_v1\model;


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

    public static function getAllByModuleIdAndPlatform(int $module_id , string $platform): Collection
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['platform' , '=' , $platform] ,
            ])
            ->orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

}
