<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\CollectionModel;
use App\Customize\api\web_v1\model\PraiseModel;
use App\Customize\api\web_v1\model\RelationTagModel;
use App\Customize\api\web_v1\model\CategoryModel;
use App\Customize\api\web_v1\model\VideoModel;
use App\Customize\api\web_v1\model\VideoSubjectModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\model\SubjectModel;
use App\Customize\api\web_v1\model\UserModel;
use App\Customize\api\web_v1\util\FileUtil;
use stdClass;
use function api\web_v1\get_value;
use function api\web_v1\user;
use function core\convert_obj;

class VideoSubjectHandler extends Handler
{
    public static function handle(?VideoSubjectModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $module = ModuleModel::find($res->module_id);
        $module = ModuleHandler::handle($module);

        $videos = VideoModel::getByVideoSubjectId($res->id);
        $videos = VideoHandler::handleAll($videos);

        $tags = RelationTagModel::getByRelationTypeAndRelationId('video_subject' , $res->id);

        $res->module = $module;
        $res->tags = $tags;
        $res->videos = $videos;

        $res->__thumb__ = empty($res->thumb) ? '' : FileUtil::url($res->thumb);
        $res->__status__ = get_value('business.status_for_video_subject' , $res->status);

        // 点赞数
        $res->play_count = VideoModel::sumPlayCountByVideoSubjectId($res->id);
        // 观看数
        $res->view_count = VideoModel::sumViewCountByVideoSubjectId($res->id);
        // 点赞数
        $res->praise_count = VideoModel::sumPraiseCountByVideoSubjectId($res->id);
        // 反对数
        $res->against_count = VideoModel::sumAgainstCountByVideoSubjectId($res->id);
        // 收藏数
        $res->collect_count = CollectionModel::countByModuleIdAndRelationTypeAndRelationId($res->module_id , 'video_subject' , $res->id);

        $user = user();
        if (empty($user)) {
            $res->collected = 0;
            $res->praised   = 0;
        } else {
            $res->collected = CollectionModel::findByModuleIdAndUserIdAndRelationTypeAndRelationId($res->module_id , $user->id , 'video_subject' , $res->id) ? 1 : 0;
            $res->praised   = PraiseModel::getByModuleIdAndUserIdAndRelationTypeAndRelationIds($res->module_id , $user->id , 'video' , array_column($videos , 'id'))->count() ? 1 : 0;
        }

        $res->format_time = date('Y-m-d H:i' , strtotime($res->create_time));

        return $res;
    }

}
