<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Collection;

class ResourceModel extends Model
{
    //
    protected $table = 'xq_resource';

    public static function getWaitDeleteByLimitIdAndLimit(int $limit_id = 0 , int $limit = 20): Collection
    {
        return self::where(function($query){
                $query->where('used' , 0)
                    ->orWhere('is_deleted' , 1);
            })
            ->where('id' , '>' , $limit_id)
            ->limit($limit)
            ->get();
    }
}
