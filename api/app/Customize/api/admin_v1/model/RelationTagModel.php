<?php


namespace App\Customize\api\admin_v1\model;


use Illuminate\Database\Eloquent\Collection;

class RelationTagModel extends Model
{
    protected $table = 'xq_relation_tag';

    public static function getByRelationTypeAndRelationId(string $relation_type , int $relation_id): Collection
    {
        return self::where([
                    ['relation_type' , '=' , $relation_type] ,
                    ['relation_id' , '=' , $relation_id] ,
                ])
                ->get();
    }

    public static function delByRelationTypeAndRelationId(string $relation_type , int $relation_id): int
    {
        return self::where([
                    ['relation_type' , '=' , $relation_type] ,
                    ['relation_id' , '=' , $relation_id] ,
                ])
                ->delete();
    }

    public static function delByRelationTypeAndRelationIdAndTagId(string $relation_type , int $relation_id , int $tag_id): int
    {
        return self::where([
            ['relation_type' , '=' , $relation_type] ,
            ['relation_id' , '=' , $relation_id] ,
            ['tag_id' , '=' , $tag_id] ,
        ])->delete();
    }
}
