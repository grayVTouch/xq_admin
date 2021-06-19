<?php


namespace App\Customize\api\web\model;


use Illuminate\Database\Eloquent\Collection;

class UserVideoProjectPlayRecordModel extends Model
{
    protected $table = 'xq_user_video_project_play_record';

    public static function findByModuleIdAndUserIdAndVideoProjectId(int $module_id , int $user_id , int $video_project_id): ?UserVideoProjectPlayRecordModel
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['user_id' , '=' , $user_id] ,
            ['video_project_id' , '=' , $video_project_id] ,
        ])->first();
    }
}
