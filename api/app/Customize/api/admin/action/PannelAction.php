<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/2/19
 * Time: 15:48
 */

namespace App\Customize\api\admin\action;

use App\Customize\api\admin\model\AdminModel;
use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\FailedJobsModel;
use App\Customize\api\admin\model\ImageProjectModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\ImageSubjectModel;
use App\Customize\api\admin\model\TagModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\model\VideoCompanyModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\VideoSeriesModel;
use App\Customize\api\admin\model\VideoProjectModel;
use App\Customize\api\admin\util\PannelUtil;
use App\Http\Controllers\api\admin\Base;
use App\Http\Controllers\api\admin\VideoCompany;
use App\Http\Controllers\api\web\Subject;

class PannelAction extends Action
{
    public static function info(Base $base , array $param = [])
    {
        $today = date('Y-m-d');
        $yesterday  = date_create('yesterday')->format('Y-m-d');

        /**
         * 今日
         */

        // 普通用户
        $user_count_for_today                       = UserModel::countByDate($today);
        // 后台用户
        $admin_count_for_today                  = AdminModel::countByDate($today);
        // 图片专题
        $image_project_count_for_today          = ImageProjectModel::countByDate($today);
        // 标签
        $tag_count_for_today                    = TagModel::countByDate($today);
        // 模块
        $module_count_for_today                  = ModuleModel::countByDate($today);
        // 关联主体
        $subject_count_for_today                  = ImageSubjectModel::countByDate($today);
        // 视频数量
        $video_count_for_today                  = VideoModel::countByDate($today);
        // 视频专题
        $video_project_count_for_today          = VideoProjectModel::countByDate($today);
        // 视频系列
        $video_series_count_for_today                  = VideoSeriesModel::countByDate($today);
        // 视频制作公司
        $video_company_count_for_today                  = VideoCompanyModel::countByDate($today);
        // 分类
        $category_count_for_today = CategoryModel::countByDate($today);
        // 失败队列
        $failed_jobs_count_for_today = FailedJobsModel::countByDate($today);
        // 视频：处理失败的视频
        $processed_video_count_for_today = VideoModel::countByDateAndProcessStatus($today , 2);
        // 视频：处理成功的视频
        $process_failed_video_count_for_today = VideoModel::countByDateAndProcessStatus($today , -1);

        // 昨日
        $user_count_for_yesterday           = UserModel::countByDate($yesterday);
        $admin_count_for_yesterday          = AdminModel::countByDate($yesterday);
        $image_project_count_for_yesterday  = ImageProjectModel::countByDate($yesterday);
        $tag_count_for_yesterday            = TagModel::countByDate($yesterday);
        $module_count_for_yesterday         = ModuleModel::countByDate($yesterday);
        $subject_count_for_yesterday        = ImageSubjectModel::countByDate($yesterday);
        $video_count_for_yesterday          = VideoModel::countByDate($yesterday);
        $video_project_count_for_yesterday  = VideoProjectModel::countByDate($yesterday);
        $video_series_count_for_yesterday   = VideoSeriesModel::countByDate($yesterday);
        $video_company_count_for_yesterday  = VideoCompanyModel::countByDate($yesterday);
        $category_count_for_yesterday  = CategoryModel::countByDate($yesterday);
        $failed_jobs_count_for_yesterday = FailedJobsModel::countByDate($yesterday);
        $processed_video_count_for_yesterday = VideoModel::countByDateAndProcessStatus($yesterday , 2);
        $process_failed_video_count_for_yesterday = VideoModel::countByDateAndProcessStatus($yesterday , -1);

        // 比例
        $ratio_for_user             = PannelUtil::ratio($user_count_for_today , $user_count_for_yesterday);
        $ratio_for_admin            = PannelUtil::ratio($admin_count_for_today , $admin_count_for_yesterday);
        $ratio_for_image_project    = PannelUtil::ratio($image_project_count_for_today , $image_project_count_for_yesterday);
        $ratio_for_subject          = PannelUtil::ratio($subject_count_for_today , $subject_count_for_yesterday);
        $ratio_for_tag              = PannelUtil::ratio($tag_count_for_today , $tag_count_for_yesterday);
        $ratio_for_module           = PannelUtil::ratio($module_count_for_today , $module_count_for_yesterday);
        $ratio_for_video    = PannelUtil::ratio($video_count_for_today , $video_count_for_yesterday);
        $ratio_for_video_project    = PannelUtil::ratio($video_project_count_for_today , $video_project_count_for_yesterday);
        $ratio_for_video_series    = PannelUtil::ratio($video_series_count_for_today , $video_series_count_for_yesterday);
        $ratio_for_video_company    = PannelUtil::ratio($video_company_count_for_today , $video_company_count_for_yesterday);
        $ratio_for_category    = PannelUtil::ratio($category_count_for_today , $category_count_for_yesterday);
        $ratio_for_failed_jobs    = PannelUtil::ratio($failed_jobs_count_for_today , $failed_jobs_count_for_yesterday);
        $ratio_for_processed_video    = PannelUtil::ratio($processed_video_count_for_today , $processed_video_count_for_yesterday);
        $ratio_for_process_failed_video    = PannelUtil::ratio($process_failed_video_count_for_today , $process_failed_video_count_for_yesterday);


        // 标志
        $flag_for_user = PannelUtil::flag($user_count_for_today  , $user_count_for_yesterday);
        $flag_for_admin            = PannelUtil::flag($admin_count_for_today , $admin_count_for_yesterday);
        $flag_for_image_project    = PannelUtil::flag($image_project_count_for_today , $image_project_count_for_yesterday);
        $flag_for_subject          = PannelUtil::flag($subject_count_for_today , $subject_count_for_yesterday);
        $flag_for_tag              = PannelUtil::flag($tag_count_for_today , $tag_count_for_yesterday);
        $flag_for_module           = PannelUtil::flag($module_count_for_today , $module_count_for_yesterday);
        $flag_for_video    = PannelUtil::flag($video_count_for_today , $video_count_for_yesterday);
        $flag_for_video_project    = PannelUtil::flag($video_project_count_for_today , $video_project_count_for_yesterday);
        $flag_for_video_series    = PannelUtil::flag($video_series_count_for_today , $video_series_count_for_yesterday);
        $flag_for_video_company    = PannelUtil::flag($video_company_count_for_today , $video_company_count_for_yesterday);
        $flag_for_category    = PannelUtil::flag($category_count_for_today , $category_count_for_yesterday);
        $flag_for_failed_jobs    = PannelUtil::flag($failed_jobs_count_for_today , $failed_jobs_count_for_yesterday);
        $flag_for_processed_video    = PannelUtil::flag($process_failed_video_count_for_today , $processed_video_count_for_yesterday);
        $flag_for_process_failed_video    = PannelUtil::flag($process_failed_video_count_for_today , $process_failed_video_count_for_yesterday);

        // 总计
        $user_count = UserModel::count();
        $admin_count = AdminModel::count();
        $image_project_count = ImageProjectModel::count();
        $subject_count = ImageSubjectModel::count();
        $tag_count = TagModel::count();
        $module_count = ModuleModel::count();
        $video_count = VideoModel::count();
        $video_project_count = VideoProjectModel::count();
        $video_series_count = VideoSeriesModel::count();
        $video_company_count = VideoCompanyModel::count();
        $category_count = VideoCompanyModel::count();
        $failed_jobs_count = FailedJobsModel::count();
        $processed_video_count = VideoModel::countByProcessStatus(2);
        $process_failed_video_count = VideoModel::countByProcessStatus(-1);

        return self::success('' , [
            'user' => [
                'today'     => $user_count_for_today ,
                'yesterday' => $user_count_for_yesterday ,
                'flag'      => $flag_for_user ,
                'ratio'     => $ratio_for_user ,
                'total'     => $user_count ,
            ] ,
            'admin' => [
                'today'     => $admin_count_for_today ,
                'yesterday' => $admin_count_for_yesterday ,
                'flag'      => $flag_for_admin ,
                'ratio'     => $ratio_for_admin ,
                'total'     => $admin_count ,
            ] ,
            'image_project' => [
                'today'     => $image_project_count_for_today ,
                'yesterday' => $image_project_count_for_yesterday ,
                'flag'      => $flag_for_image_project ,
                'ratio'     => $ratio_for_image_project ,
                'total'     => $image_project_count ,
            ] ,
            'image_subject' => [
                'today'     => $subject_count_for_today ,
                'yesterday' => $subject_count_for_yesterday ,
                'flag'      => $flag_for_subject ,
                'ratio'     => $ratio_for_subject ,
                'total'     => $subject_count ,
            ] ,
            'tag' => [
                'today'     => $tag_count_for_today ,
                'yesterday' => $tag_count_for_yesterday ,
                'flag'      => $flag_for_tag ,
                'ratio'     => $ratio_for_tag ,
                'total'     => $tag_count ,
            ] ,
            'module' => [
                'today'     => $module_count_for_today ,
                'yesterday' => $module_count_for_yesterday ,
                'flag'      => $flag_for_module ,
                'ratio'     => $ratio_for_module ,
                'total'     => $module_count ,
            ] ,
            'video' => [
                'today'     => $video_count_for_today ,
                'yesterday' => $video_count_for_yesterday ,
                'flag'      => $flag_for_video ,
                'ratio'     => $ratio_for_video ,
                'total'     => $video_count ,
            ] ,
            'video_project' => [
                'today'     => $video_project_count_for_today ,
                'yesterday' => $video_project_count_for_yesterday ,
                'flag'      => $flag_for_video_project ,
                'ratio'     => $ratio_for_video_project ,
                'total'     => $video_project_count ,
            ] ,
            'video_series' => [
                'today'     => $video_series_count_for_today ,
                'yesterday' => $video_series_count_for_yesterday ,
                'flag'      => $flag_for_video_series ,
                'ratio'     => $ratio_for_video_series ,
                'total'     => $video_series_count ,
            ] ,
            'video_company' => [
                'today'     => $video_company_count_for_today ,
                'yesterday' => $video_company_count_for_yesterday ,
                'flag'      => $flag_for_video_company ,
                'ratio'     => $ratio_for_video_company ,
                'total'     => $video_company_count ,
            ] ,
            'category' => [
                'today'     => $category_count_for_today ,
                'yesterday' => $category_count_for_yesterday ,
                'flag'      => $flag_for_category ,
                'ratio'     => $ratio_for_category ,
                'total'     => $category_count ,
            ] ,
            'failed_jobs' => [
                'today'     => $failed_jobs_count_for_today ,
                'yesterday' => $failed_jobs_count_for_yesterday ,
                'flag'      => $flag_for_failed_jobs ,
                'ratio'     => $ratio_for_failed_jobs ,
                'total'     => $failed_jobs_count ,
            ] ,
            'processed_video' => [
                'today'     => $processed_video_count_for_today ,
                'yesterday' => $processed_video_count_for_yesterday ,
                'flag'      => $flag_for_processed_video ,
                'ratio'     => $ratio_for_processed_video ,
                'total'     => $processed_video_count ,
            ] ,
            'process_failed_video' => [
                'today'     => $process_failed_video_count_for_today ,
                'yesterday' => $process_failed_video_count_for_yesterday ,
                'flag'      => $flag_for_process_failed_video ,
                'ratio'     => $ratio_for_process_failed_video ,
                'total'     => $process_failed_video_count ,
            ] ,
        ]);

    }
}
