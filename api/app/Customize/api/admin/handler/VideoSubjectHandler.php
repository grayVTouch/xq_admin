<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\RelationTagModel;
use App\Customize\api\admin\model\UserModel;
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

        $res->__status__    = empty($res->status) ? '' : get_value('business.status_for_video_subject' , $res->status);
        $res->release_time  = $res->release_time ?? '';
        $res->end_time      = $res->end_time ?? '';

        if (in_array('module' , $with)) {
            $module = ModuleModel::find($res->module_id);
            $module = ModuleHandler::handle($module);
            $model->module = $module;
        }
        if (in_array('video_series' , $with)) {
            $video_series = VideoSeriesModel::find($res->video_series_id);
            $video_series = VideoSeriesHandler::handle($video_series);
            $model->video_series = $video_series;
        }
        if (in_array('video_company' , $with)) {
            $video_company = VideoCompanyModel::find($res->video_company_id);
            $video_company = VideoCompanyHandler::handle($video_company);
            $model->video_company = $video_company;
        }
        if (in_array('category' , $with)) {
            $category = CategoryModel::find($res->category_id);
            $category = CategoryHandler::handle($category);
            $model->category = $category;
        }
        if (in_array('tags' , $with)) {
            $tags = RelationTagModel::getByRelationTypeAndRelationId('video_subject' , $res->id);
            $model->tags = $tags;
        }

        return $res;
    }

}
