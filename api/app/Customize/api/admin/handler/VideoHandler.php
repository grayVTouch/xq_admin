<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\RelationTagModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoSubjectModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\util\FileUtil;
use stdClass;
use function api\admin\get_value;
use function core\convert_obj;
use function core\format_time;

class VideoHandler extends Handler
{
    public static function handle(?VideoModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $user = UserModel::find($res->user_id);
        $user = UserHandler::handle($user);

        $module = ModuleModel::find($res->module_id);
        $module = ModuleHandler::handle($module);

        $category = CategoryModel::find($res->category_id);
        $category = CategoryHandler::handle($category);

        if ($res->type === 'pro') {
            $video_subject = VideoSubjectModel::find($res->video_subject_id);
            $video_subject = VideoSubjectHandler::handle($video_subject);
        } else {
            $video_subject = null;
        }

        $videos = VideoSrcModel::getByVideoId($res->id);
        $videos = VideoSrcHandler::handleAll($videos);

        $video_subtitles = VideoSubtitleModel::getByVideoId($res->id);
        $video_subtitles = VideoSubtitleHandler::handleAll($video_subtitles);

        $res->user          = $user;
        $res->module        = $module;
        $res->category      = $category;
        $res->video_subject = $video_subject;
        $res->videos        = $videos;
        $res->video_subtitles     = $video_subtitles;


        $res->__duration__          = empty($res->duration) ? 0 : format_time($res->duration , 'HH:II:SS');


        $res->__thumb_for_program__ = empty($res->thumb_for_program) ? '' : FileUtil::url($res->thumb_for_program);
        $res->__simple_preview__    = empty($res->simple_preview) ? '' : FileUtil::url($res->simple_preview);
        $res->__preview__           = empty($res->preview) ? '' : FileUtil::url($res->preview);
        $res->__type__              = get_value('business.type_for_video' , $res->type);
        $res->__status__            = get_value('business.status_for_video' , $res->status);
        $res->__process_status__    = get_value('business.process_status_for_video' , $res->process_status);


        return $res;
    }

}
