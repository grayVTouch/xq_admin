<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Database\Eloquent\Collection;

class PositionModel extends Model
{
    protected $table = 'xq_position';

    public static function getByModuleAndValue(int $module_id , string $value): ?PositionModel
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['value' , '=' , $value] ,
            ])
            ->first();
    }
}
