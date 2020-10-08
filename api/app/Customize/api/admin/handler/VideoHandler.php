<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoProjectModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\model\Model;
use stdClass;

use function api\admin\get_config_key_mapping_value;
use function core\convert_object;
use function core\format_time;
use function core\object_to_array;

class VideoHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);

        $model->__duration__          = empty($model->duration) ? 0 : format_time($model->duration , 'HH:II:SS');
        $model->__type__              = get_config_key_mapping_value('business.type_for_video' , $model->type);
        $model->__status__            = get_config_key_mapping_value('business.status_for_video' , $model->status);
        $model->__process_status__    = get_config_key_mapping_value('business.process_status_for_video' , $model->process_status);

        if (in_array('user' , $with)) {
            $user = UserModel::find($model->user_id);
            $user = UserHandler::handle($user);
            $model->user = $user;
        }
        if (in_array('module' , $with)) {
            $module = ModuleModel::find($model->module_id);
            $module = ModuleHandler::handle($module);
            $model->module = $module;
        }
        if (in_array('category' , $with)) {
            $category = CategoryModel::find($model->category_id);
            $category = CategoryHandler::handle($category);
            $model->category = $category;
        }
        if (in_array('video_project' , $with)) {
            if ($model->type === 'pro') {
                $video_project = VideoProjectModel::find($model->video_project_id);
                $video_project = VideoProjectHandler::handle($video_project , [
                    'video_series' ,
                    'video_company' ,
                    'category' ,
                    'tags' ,
                ]);
            } else {
                $video_project = null;
            }
            $model->video_project = $video_project;
        }
        if (in_array('videos' , $with)) {
            $videos = VideoSrcModel::getByVideoId($model->id);
            $videos = VideoSrcHandler::handleAll($videos);
            $model->videos = $videos;
        }
        if (in_array('video_subtitles' , $with)) {
            $video_subtitles = VideoSubtitleModel::getByVideoId($model->id);
            $video_subtitles = VideoSubtitleHandler::handleAll($video_subtitles);
            $model->video_subtitles = $video_subtitles;
        }

        return $model;
    }

}
