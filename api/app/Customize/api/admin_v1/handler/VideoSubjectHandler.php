<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\CategoryModel;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\RelationTagModel;
use App\Customize\api\admin_v1\model\VideoCompanyModel;
use App\Customize\api\admin_v1\model\VideoSeriesModel;
use App\Customize\api\admin_v1\model\VideoSubjectModel;
use App\Customize\api\admin_v1\util\FileUtil;
use stdClass;
use function api\admin_v1\get_value;
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
        ModuleHandler::handle($module);

        $video_series = VideoSeriesModel::find($res->video_series_id);
        $video_series = VideoSeriesHandler::handle($video_series);

        $video_company = VideoCompanyModel::find($res->video_company_id);
        $video_company = VideoCompanyHandler::handle($video_company);

        $category = CategoryModel::find($res->category_id);
        $category = CategoryHandler::handle($category);


        $tags = RelationTagModel::getByRelationTypeAndRelationId('video_subject' , $res->id);

        $res->module = $module;
        $res->video_series = $video_series;
        $res->video_company = $video_company;
        $res->tags = $tags;
        $res->category = $category;

        $res->__thumb__ = empty($res->thumb) ? '' : FileUtil::url($res->thumb);
        $res->__status__ = empty($res->status) ? '' : get_value('business.status_for_video_subject' , $res->status);
        $res->release_time = $res->release_time ?? '';
        $res->end_time = $res->end_time ?? '';

        return $res;
    }

}
