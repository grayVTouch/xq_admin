<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoSubjectModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\model\Model;
use stdClass;

use function api\admin\get_config_key_mapping_value;
use function core\convert_object;
use function core\format_time;

class VideoHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        $res->__duration__          = empty($res->duration) ? 0 : format_time($res->duration , 'HH:II:SS');
        $res->__type__              = get_config_key_mapping_value('business.type_for_video' , $res->type);
        $res->__status__            = get_config_key_mapping_value('business.status_for_video' , $res->status);
        $res->__process_status__    = get_config_key_mapping_value('business.process_status_for_video' , $res->process_status);

        if (in_array('user' , $with)) {
            $user = UserModel::find($res->user_id);
            $user = UserHandler::handle($user);
            $model->user = $user;
        }
        if (in_array('module' , $with)) {
            $module = ModuleModel::find($res->module_id);
            $module = ModuleHandler::handle($module);
            $res->module = $module;
        }
        if (in_array('category' , $with)) {
            $category = CategoryModel::find($res->category_id);
            $category = CategoryHandler::handle($category);
            $res->category = $category;
        }
        if (in_array('video_subject' , $with)) {
            if ($res->type === 'pro') {
                $video_subject = VideoSubjectModel::find($res->video_subject_id);
                $video_subject = VideoSubjectHandler::handle($video_subject , [
                    'video_series' ,
                    'video_company' ,
                    'category' ,
                    'tags' ,
                ]);
            } else {
                $video_subject = null;
            }
            $res->video_subject = $video_subject;
        }
        if (in_array('videos' , $with)) {
            $videos = VideoSrcModel::getByVideoId($res->id);
            $videos = VideoSrcHandler::handleAll($videos);
            $res->videos = $videos;
        }
        if (in_array('video_subtitles' , $with)) {
            $video_subtitles = VideoSubtitleModel::getByVideoId($res->id);
            $video_subtitles = VideoSubtitleHandler::handleAll($video_subtitles);
            $res->video_subtitles = $video_subtitles;
        }

        return $res;
    }

}
