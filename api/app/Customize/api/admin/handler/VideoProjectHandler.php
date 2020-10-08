<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\RelationTagModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\model\VideoCompanyModel;
use App\Customize\api\admin\model\VideoSeriesModel;
use App\Customize\api\admin\model\Model;
use stdClass;

use function api\admin\get_config_key_mapping_value;
use function core\convert_object;

class VideoProjectHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);

        $model->__end_status__  = empty($model->end_status) ? '' : get_config_key_mapping_value('business.end_status_for_video_project' , $model->status);
        $model->__status__      = empty($model->status) ? '' : get_config_key_mapping_value('business.status_for_video_project' , $model->status);

        if (in_array('module' , $with)) {
            $module = ModuleModel::find($model->module_id);
            $module = ModuleHandler::handle($module);
            $model->module = $module;
        }
        if (in_array('user' , $with)) {
            $user = UserModel::find($model->user_id);
            $user = UserHandler::handle($user);
            $model->user = $user;
        }
        if (in_array('video_series' , $with)) {
            $video_series = VideoSeriesModel::find($model->video_series_id);
            $video_series = VideoSeriesHandler::handle($video_series);
            $model->video_series = $video_series;
        }
        if (in_array('video_company' , $with)) {
            $video_company = VideoCompanyModel::find($model->video_company_id);
            $video_company = VideoCompanyHandler::handle($video_company);
            $model->video_company = $video_company;
        }
        if (in_array('category' , $with)) {
            $category = CategoryModel::find($model->category_id);
            $category = CategoryHandler::handle($category);
            $model->category = $category;
        }
        if (in_array('tags' , $with)) {
            $tags = RelationTagModel::getByRelationTypeAndRelationId('video_project' , $model->id);
            $model->tags = $tags;
        }

        return $model;
    }

}
