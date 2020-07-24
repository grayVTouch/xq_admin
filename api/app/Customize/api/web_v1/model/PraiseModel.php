<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Database\Eloquent\Collection;

class PraiseModel extends Model
{
    protected $table = 'xq_praise';

    public static function findByModuleIdAndUserIdAndRelationTypeAndRelationId(int $module_id ,int $user_id , string $relation_type , int $relation_id): ?PraiseModel
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
                ['relation_type' , '=' , $relation_type] ,
                ['relation_id' , '=' , $relation_id] ,
            ])
            ->first();
    }

    public static function delByModuleIdAndUserIdAndRelationTypeAndRelationId(int $module_id ,int $user_id , string $relation_type , int $relation_id): int
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['relation_type' , '=' , $relation_type] ,
            ['relation_id' , '=' , $relation_id] ,
        ])
            ->delete();
    }

    public static function countByUserId(int $user_id)
    {
        return self::where('user_id' , $user_id)
            ->count();
    }
}
