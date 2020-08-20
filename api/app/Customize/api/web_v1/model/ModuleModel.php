<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Database\Eloquent\Collection;

class ModuleModel extends Model
{
    protected $table = 'xq_module';

    public static function getAll() :Collection
    {
        return self::where('enable' , 1)
            ->orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

    public static function getDefault(): ?ModuleModel
    {
        return self::where([
            ['enable' , '=' , 1] ,
            ['default' , '=' , 1] ,
        ])->first();
    }
}
