<?php


namespace App\Customize\api\web\model;


use Illuminate\Database\Eloquent\Collection;

class ModuleModel extends Model
{
    protected $table = 'xq_module';

    public static function getAll() :Collection
    {
        return self::where('is_enabled' , 1)
            ->orderBy('weight' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

    public static function getDefault(): ?ModuleModel
    {
        return self::where([
            ['is_enabled' , '=' , 1] ,
            ['is_default' , '=' , 1] ,
        ])->first();
    }
}
