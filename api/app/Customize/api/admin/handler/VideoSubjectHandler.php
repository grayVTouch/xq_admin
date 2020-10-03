<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\RelationTagModel;
use App\Customize\api\admin\model\VideoCompanyModel;
use App\Customize\api\admin\model\VideoSeriesModel;
use App\Customize\api\admin\model\VideoSubjectModel;
use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\model\Model;
use stdClass;

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


        $res->__status__ = empty($res->status) ? '' : get_value('business.status_for_video_subject' , $res->status);
        $res->release_time = $res->release_time ?? '';
        $res->end_time = $res->end_time ?? '';

        return $res;
    }

}
