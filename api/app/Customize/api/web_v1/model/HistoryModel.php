<?php


namespace App\Customize\api\web_v1\model;



use Illuminate\Contracts\Pagination\Paginator;

class HistoryModel extends Model
{
    protected $table = 'xq_history';

    // 获取给定数量的历史记录（按照时间倒叙排序）
    public static function getOrderTimeByModuleIdAndUserIdAndLimit(int $module_id , int $user_id , int $limit = 20)
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
            ])
            ->orderBy('create_time' , 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getByModuleIdAndUserIdAndRelationTypeAndValueAndLimit(int $module_id , int $user_id , string $relation_type = '' , string $value = '' , int $limit = 20): Paginator
    {
        $where = [
            ['h.module_id' , '=' , $module_id] ,
            ['h.user_id' , '=' , $user_id] ,
        ];
        if (!empty($relation_type)) {
            $where[] = ['h.relation_type' , '=' , $relation_type];
        }
        if (!empty($value)) {
            $where[] = ['' , '=' , $relation_type];
        }
        return self::select('h.*' , 'is.name as name_for_image_subject')
            ->from('xq_history as h')
            ->leftJoin('xq_image_subject as is' , function($join){
                // $join->on 会把内容当成是字段
                // $join->where 仅把值当成是值
                $join->where([
                    ['h.relation_id' , '=' , 'is.id'] ,
                    ['h.relation_type' , '=' , 'image_subject']
                ]);
            })
            ->where($where)
            ->having('name_for_image_subject' , 'like' , "%{$value}%")
            ->paginate($limit);
    }

    public static function findByModuleIdAndUserIdAndRealtionTableAndDate(int $module_id , int $user_id , string $relation_type , string $date)
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['user_id' , '=' , $user_id] ,
                ['relation_type' , '=' , $relation_type] ,
                ['date' , '=' , $date] ,
            ])
            ->first();
    }
}
