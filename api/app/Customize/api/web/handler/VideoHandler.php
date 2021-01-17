<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\VideoSubtitleModel;
use App\Customize\api\web\model\VideoSrcModel;
use App\Customize\api\web\model\VideoModel;
use App\Customize\api\web\util\FileUtil;
use App\Customize\api\web\model\Model;
use stdClass;
use function core\convert_object;
use function core\format_time;

class VideoHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);

        $videos = VideoSrcModel::getByVideoId($model->id);
        $videos = VideoSrcHandler::handleAll($videos);

        $video_subtitles = VideoSubtitleModel::getByVideoId($model->id);
        $video_subtitles = VideoSubtitleHandler::handleAll($video_subtitles);

        $model->videos = $videos;
        $model->video_subtitles = $video_subtitles;


        $model->__duration__          = empty($model->duration) ? 0 : format_time($model->duration , 'HH:II:SS');
        $model->__thumb__ = empty($model->thumb) ? $model->thumb_for_program : $model->thumb;

        return $model;
    }
}
