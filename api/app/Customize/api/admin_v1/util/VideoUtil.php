<?php


namespace App\Customize\api\admin_v1\util;


use App\Customize\api\admin_v1\handler\VideoHandler;
use App\Customize\api\admin_v1\model\VideoCommentImageModel;
use App\Customize\api\admin_v1\model\VideoCommentModel;
use App\Customize\api\admin_v1\model\VideoModel;
use App\Customize\api\admin_v1\model\VideoSrcModel;

class VideoUtil
{
    public static function delete(int $id): ?bool
    {
        $res = VideoModel::find($id);
        if (empty($res)) {
            return false;
        }
        $res = VideoHandler::handle($res);
        ResourceUtil::delete($res->thumb);
        ResourceUtil::delete($res->thumb_for_program);
        ResourceUtil::delete($res->simple_preview);
        ResourceUtil::delete($res->preview);
        ResourceUtil::delete($res->src);
        // 删除相关视频源文件
        foreach ($res->videos as $v)
        {
            ResourceUtil::delete($v->src);
        }
        // 删除相关字幕文件
        foreach ($res->subtitles as $v)
        {
            ResourceUtil::delete($v->src);
        }
        $video_comment_images = VideoCommentImageModel::getByVideoId($res->id);;
        foreach ($video_comment_images as $v)
        {
            ResourceUtil::delete($v->path);
        }
        VideoModel::destroy($res->id);
        VideoSrcModel::delByVideoId($res->id);
        VideoCommentModel::delByVideoId($res->id);
        VideoCommentImageModel::delByVideoId($res->id);
        return null;
    }
}
