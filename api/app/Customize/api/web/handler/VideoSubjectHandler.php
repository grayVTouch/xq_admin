<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\handler\VideoCompanyHandler;
use App\Customize\api\web\handler\VideoSeriesHandler;
use App\Customize\api\web\model\VideoSeriesModel;
use App\Customize\api\web\model\VideoCompanyModel;
use App\Customize\api\web\model\CollectionModel;
use App\Customize\api\web\model\PraiseModel;
use App\Customize\api\web\model\RelationTagModel;
use App\Customize\api\web\model\CategoryModel;
use App\Customize\api\web\model\VideoModel;
use App\Customize\api\web\model\VideoSubjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\util\FileUtil;
use App\Model\Model;
use stdClass;
use function api\admin\get_config_key_mapping_value;
use function api\web\get_value;
use function api\web\user;
use function core\convert_object;

class VideoSubjectHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        $module = ModuleModel::find($res->module_id);
        $module = ModuleHandler::handle($module);

        $video_series = VideoSeriesModel::find($res->video_series_id);
        $video_series = VideoSeriesHandler::handle($video_series);

        $video_company = VideoCompanyModel::find($res->video_company_id);
        $video_company = VideoCompanyHandler::handle($video_company);

        $category = CategoryModel::find($res->category_id);
        $category = CategoryHandler::handle($category);

        $videos = VideoModel::getByVideoSubjectId($res->id);
        $videos = VideoHandler::handleAll($videos);

        $tags = RelationTagModel::getByRelationTypeAndRelationId('video_subject' , $res->id);

        $res->module = $module;
        $res->video_series = $video_series;
        $res->video_company = $video_company;
        $res->tags = $tags;
        $res->category = $category;
        $res->videos = $videos;


        $res->__status__ = get_config_key_mapping_value('business.status_for_video_subject' , $res->status);

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

        $res->format_time = date('Y-m-d H:i' , strtotime($res->created_at));

        return $res;
    }

}
