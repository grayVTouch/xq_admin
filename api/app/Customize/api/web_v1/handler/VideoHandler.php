<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\VideoSubtitleModel;
use App\Customize\api\web_v1\model\VideoSrcModel;
use App\Customize\api\web_v1\model\VideoModel;
use App\Customize\api\web_v1\util\FileUtil;
use stdClass;
use function core\convert_obj;

class VideoHandler extends Handler
{
    public static function handle(?VideoModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_obj($model);

        $videos = VideoSrcModel::getByVideoId($model->id);
        $videos = VideoSrcHandler::handleAll($videos);

        $video_subtitles = VideoSubtitleModel::getByVideoId($model->id);
        $video_subtitles = VideoSubtitleHandler::handleAll($video_subtitles);

        $model->videos = $videos;
        $model->video_subtitles = $video_subtitles;

        $model->__preview__         = empty($model->preview) ? '' : FileUtil::url($model->preview);
        $model->__simple_preview__  = empty($model->simple_preview) ? '' : FileUtil::url($model->simple_preview);
        $model->__thumb__ = empty($model->thumb) ?
            (empty($model->thumb_for_program) ? '' : FileUtil::url($model->thumb_for_program)) :
            FileUtil::url($model->thumb);
        $model->__thumb_for_program__ = empty($model->thumb_for_program) ? '' : FileUtil::url($model->thumb_for_program);
        return $model;
    }
}
