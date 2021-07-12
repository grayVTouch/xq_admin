<?php


namespace App\Customize\api\admin\repository;


use App\Customize\api\admin\handler\VideoHandler;
use App\Customize\api\admin\model\VideoCommentImageModel;
use App\Customize\api\admin\model\VideoCommentModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\VideoSrcModel;

class VideoRepository
{
    public static function delete(int $id): ?bool
    {
        $res = VideoModel::find($id);
        if (empty($res)) {
            return false;
        }
        $res = VideoHandler::handle($res);

        VideoHandler::videos($res);
        VideoHandler::videoSubtitles($res);
        VideoHandler::videoProject($res);

        ResourceRepository::delete($res->thumb);
        ResourceRepository::delete($res->thumb_for_program);
        ResourceRepository::delete($res->simple_preview);
        ResourceRepository::delete($res->preview);
        ResourceRepository::delete($res->src);
        // 删除相关视频源文件
        foreach ($res->videos as $v)
        {
            ResourceRepository::delete($v->src);
        }
        // 删除相关字幕文件
        foreach ($res->video_subtitles as $v)
        {
            ResourceRepository::delete($v->src);
        }
        $video_comment_images = VideoCommentImageModel::getByVideoId($res->id);;
        foreach ($video_comment_images as $v)
        {
            ResourceRepository::delete($v->path);
        }
        VideoModel::destroy($res->id);
        VideoSrcModel::delByVideoId($res->id);
        VideoCommentModel::delByVideoId($res->id);
        VideoCommentImageModel::delByVideoId($res->id);
        return null;
    }
}
