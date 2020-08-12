<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/2/19
 * Time: 15:48
 */

namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\model\AdminModel;
use App\Customize\api\admin_v1\model\ImageSubjectModel;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\SubjectModel;
use App\Customize\api\admin_v1\model\TagModel;
use App\Customize\api\admin_v1\model\UserModel;
use App\Customize\api\admin_v1\model\VideoCompanyModel;
use App\Customize\api\admin_v1\model\VideoModel;
use App\Customize\api\admin_v1\model\VideoSeriesModel;
use App\Customize\api\admin_v1\model\VideoSubjectModel;
use App\Customize\api\admin_v1\util\PannelUtil;
use App\Http\Controllers\api\admin_v1\Base;
use App\Http\Controllers\api\admin_v1\VideoCompany;
use App\Http\Controllers\api\web_v1\Subject;

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
        $image_subject_count_for_today          = ImageSubjectModel::countByDate($today);
        // 标签
        $tag_count_for_today                    = TagModel::countByDate($today);
        // 模块
        $module_count_for_today                  = ModuleModel::countByDate($today);
        // 关联主体
        $subject_count_for_today                  = SubjectModel::countByDate($today);
        // 视频数量
        $video_count_for_today                  = VideoModel::countByDate($today);
        // 视频专题
        $video_subject_count_for_today          = VideoSubjectModel::countByDate($today);
        // 视频系列
        $video_series_count_for_today                  = VideoSeriesModel::countByDate($today);
        // 视频制作公司
        $video_company_count_for_today                  = VideoCompanyModel::countByDate($today);

        // 昨日
        $user_count_for_yesterday           = UserModel::countByDate($yesterday);
        $admin_count_for_yesterday          = AdminModel::countByDate($yesterday);
        $image_subject_count_for_yesterday  = ImageSubjectModel::countByDate($yesterday);
        $tag_count_for_yesterday            = TagModel::countByDate($yesterday);
        $module_count_for_yesterday         = ModuleModel::countByDate($yesterday);
        $subject_count_for_yesterday        = SubjectModel::countByDate($yesterday);
        $video_count_for_yesterday          = VideoModel::countByDate($yesterday);
        $video_subject_count_for_yesterday  = VideoSubjectModel::countByDate($yesterday);
        $video_series_count_for_yesterday   = VideoSeriesModel::countByDate($yesterday);
        $video_company_count_for_yesterday  = VideoCompanyModel::countByDate($yesterday);

        // 比例
        $ratio_for_user             = PannelUtil::ratio($user_count_for_today , $user_count_for_yesterday);
        $ratio_for_admin            = PannelUtil::ratio($admin_count_for_today , $admin_count_for_yesterday);
        $ratio_for_image_subject    = PannelUtil::ratio($image_subject_count_for_today , $image_subject_count_for_yesterday);
        $ratio_for_subject          = PannelUtil::ratio($subject_count_for_today , $subject_count_for_yesterday);
        $ratio_for_tag              = PannelUtil::ratio($tag_count_for_today , $tag_count_for_yesterday);
        $ratio_for_module           = PannelUtil::ratio($module_count_for_today , $module_count_for_yesterday);
        $ratio_for_video    = PannelUtil::ratio($video_count_for_today , $video_count_for_yesterday);
        $ratio_for_video_subject    = PannelUtil::ratio($video_subject_count_for_today , $video_subject_count_for_yesterday);
        $ratio_for_video_series    = PannelUtil::ratio($video_series_count_for_today , $video_series_count_for_yesterday);
        $ratio_for_video_company    = PannelUtil::ratio($video_company_count_for_today , $video_company_count_for_yesterday);

        // 标志
        $flag_for_user = PannelUtil::flag($user_count_for_today  , $user_count_for_yesterday);
        $flag_for_admin            = PannelUtil::flag($admin_count_for_today , $admin_count_for_yesterday);
        $flag_for_image_subject    = PannelUtil::flag($image_subject_count_for_today , $image_subject_count_for_yesterday);
        $flag_for_subject          = PannelUtil::flag($subject_count_for_today , $subject_count_for_yesterday);
        $flag_for_tag              = PannelUtil::flag($tag_count_for_today , $tag_count_for_yesterday);
        $flag_for_module           = PannelUtil::flag($module_count_for_today , $module_count_for_yesterday);
        $flag_for_video    = PannelUtil::flag($video_count_for_today , $video_count_for_yesterday);
        $flag_for_video_subject    = PannelUtil::flag($video_subject_count_for_today , $video_subject_count_for_yesterday);
        $flag_for_video_series    = PannelUtil::flag($video_series_count_for_today , $video_series_count_for_yesterday);
        $flag_for_video_company    = PannelUtil::flag($video_company_count_for_today , $video_company_count_for_yesterday);

        // 总计
        $user_count = UserModel::count();
        $admin_count = AdminModel::count();
        $image_subject_count = ImageSubjectModel::count();
        $subject_count = SubjectModel::count();
        $tag_count = TagModel::count();
        $module_count = ModuleModel::count();
        $video_count = VideoModel::count();
        $video_subject_count = VideoSubjectModel::count();
        $video_series_count = VideoSeriesModel::count();
        $video_company_count = VideoCompanyModel::count();

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
            'image_subject' => [
                'today'     => $image_subject_count_for_today ,
                'yesterday' => $image_subject_count_for_yesterday ,
                'flag'      => $flag_for_image_subject ,
                'ratio'     => $ratio_for_image_subject ,
                'total'     => $image_subject_count ,
            ] ,
            'subject' => [
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
            'video_subject' => [
                'today'     => $video_subject_count_for_today ,
                'yesterday' => $video_subject_count_for_yesterday ,
                'flag'      => $flag_for_video_subject ,
                'ratio'     => $ratio_for_video_subject ,
                'total'     => $video_subject_count ,
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
        ]);

    }
}
