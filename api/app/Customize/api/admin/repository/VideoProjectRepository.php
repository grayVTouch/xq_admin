<?php


namespace App\Customize\api\admin\repository;


use App\Customize\api\admin\handler\VideoProjectHandler;
use App\Customize\api\admin\model\RelationTagModel;
use App\Customize\api\admin\model\VideoProjectModel;

class VideoProjectRepository
{
    public static function destroy(int $id): void
    {
        $res = VideoProjectModel::find($id);
        VideoProjectHandler::videos($res);

        // 删除封面
        ResourceRepository::delete($res->thumb);
        // 删除相关视频
        foreach ($res->videos as $v)
        {
            VideoRepository::delete($v->id);
        }
        // 删除相关标签
        RelationTagModel::delByRelationTypeAndRelationId('video_project' , $res->id);
        // 专题目录删除
        ResourceRepository::delete($res->directory);
        VideoProjectModel::destroy($res->id);
    }
}
