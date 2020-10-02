<?php


namespace App\Customize\api\web\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class FocusUserModel extends Model
{
    protected $table = 'xq_focus_user';

    public static function findByUserIdAndFocusUserId(int $user_id , int $focus_user_id): ?FocusUserModel
    {
        return self::where([
                ['user_id' , '=' , $user_id] ,
                ['focus_user_id' , '=' , $focus_user_id] ,
            ])
            ->first();
    }

    public static function delByUserIdAndFocusUserId(int $user_id , int $focus_user_id): int
    {
        return self::where([
                ['user_id' , '=' , $user_id] ,
                ['focus_user_id' , '=' , $focus_user_id] ,
            ])
            ->delete();
    }

    public static function countByUserId(int $user_id): int
    {
        return self::where('user_id' , $user_id)->count();
    }

    public static function countByFocusUserId(int $focus_user_id): int
    {
        return self::where('focus_user_id' , $focus_user_id)->count();
    }

    public static function getWithPagerByUserIdAndLimit(int $user_id , int $limit = 20): Paginator
    {
        return self::where('user_id' , $user_id)
            ->paginate($limit);
    }

    public static function getWithPagerByFocusUserIdAndLimit(int $focus_user_id , int $limit = 20): Paginator
    {
        return self::where('focus_user_id' , $focus_user_id)
            ->paginate($limit);
    }
}
