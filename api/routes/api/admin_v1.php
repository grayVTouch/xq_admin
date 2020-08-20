<?php

use App\Customize\api\admin_v1\middleware\CustomizeMiddleware;
use App\Customize\api\admin_v1\middleware\UserAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('admin_v1')
    ->namespace('api\admin_v1\\')
    ->middleware([
        CustomizeMiddleware::class
    ])
    ->name('api.admin_v1.')
    ->group(function(){
        Route::middleware([])
            ->group(function(){
                Route::any('test' , 'Test@index');


                // 不用登录的相关接口
                Route::get('captcha' , 'Misc@captcha');
                Route::post('login' , 'Login@login');
                Route::get('avatar' , 'Login@avatar');

                Route::post('upload' , 'File@upload');
                Route::post('upload_image' , 'File@uploadImage');
                Route::post('upload_video' , 'File@uploadVideo');
                Route::post('upload_subtitle' , 'File@uploadSubtitle');
                Route::post('upload_office' , 'File@uploadOffice');
            });

        Route::middleware([
            UserAuthMiddleware::class
        ])->group(function(){
            // 要求用户登录的相关接口

            /**
             * *****************************
             * 后台用户
             * *****************************
             */
            Route::get('info' , 'Admin@info');
            Route::get('search_admin' , 'Admin@search');
            Route::get('admin' , 'Admin@index');
            Route::get('admin/{id}' , 'Admin@show');
            Route::put('admin/{id}' , 'Admin@update');
            Route::post('admin' , 'Admin@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_admin' , 'Admin@destroyAll');
            Route::delete('admin/{id}' , 'Admin@destroy');

            /**
             * ********************
             * 用户相关
             * ********************
             */
            Route::get('search_user' , 'User@search');
            Route::get('user' , 'User@index');
            Route::get('user/{id}' , 'User@show');
            Route::put('user/{id}' , 'User@update');
            Route::post('user' , 'User@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_user' , 'User@destroyAll');
            Route::delete('user/{id}' , 'User@destroy');

            /**
             * 权限管理
             */
            Route::get('admin_permission' , 'AdminPermission@index');
            Route::get('admin_permission/{id}' , 'AdminPermission@show');
            Route::get('admin_permission/{id}/all' , 'AdminPermission@allExcludeSelfAndChildren');
            Route::patch('admin_permission/{id}' , 'AdminPermission@localUpdate');
            Route::put('admin_permission/{id}' , 'AdminPermission@update');
            Route::post('admin_permission' , 'AdminPermission@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_admin_permission/' , 'AdminPermission@destroyAll');
            Route::delete('admin_permission/{id}' , 'AdminPermission@destroy');

            /**
             * ******************
             * 角色管理
             * ******************
             */
            Route::get('role' , 'Role@index');
            Route::get('role/{id}' , 'Role@show');
            Route::get('role/{id}/permission' , 'Role@permission');
            Route::patch('role/{id}' , 'Role@localUpdate');
            Route::put('role/{id}' , 'Role@update');
            Route::post('role' , 'Role@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_role' , 'Role@destroyAll');
            Route::delete('role/{id}' , 'Role@destroy');
            Route::post('role/{id}/allocate_permission' , 'Role@allocatePermission');
            Route::get('all_role' , 'Role@all');


            /**
             * ******************
             * 模块管理
             * ******************
             */
            Route::get('module' , 'Module@index');
            Route::get('module/{id}' , 'Module@show');
            Route::patch('module/{id}' , 'Module@localUpdate');
            Route::put('module/{id}' , 'Module@update');
            Route::post('module' , 'Module@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_module' , 'Module@destroyAll');
            Route::delete('module/{id}' , 'Module@destroy');
            Route::get('get_all_module' , 'Module@all');


            /**
             * ******************
             * 标签管理
             * ******************
             */
            Route::get('tag' , 'Tag@index');
            Route::get('tag/{id}' , 'Tag@show');
            Route::patch('tag/{id}' , 'Tag@localUpdate');
            Route::put('tag/{id}' , 'Tag@update');
            Route::post('tag' , 'Tag@store');
            Route::post('find_or_create_tag' , 'Tag@findOrCreate');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_tag' , 'Tag@destroyAll');
            Route::delete('tag/{id}' , 'Tag@destroy');
            Route::get('search_tag' , 'Tag@search');
            Route::get('top_by_module_id' , 'Tag@topByModuleId');


            /**
             * 分类管理
             */
            Route::get('category' , 'Category@index');
            Route::get('search_category_by_module_id' , 'Category@searchByModuleId');
            Route::get('category/{id}/all' , 'Category@allExcludeSelfAndChildren');
            Route::get('category/{id}' , 'Category@show');
            Route::patch('category/{id}' , 'Category@localUpdate');
            Route::put('category/{id}' , 'Category@update');
            Route::post('category' , 'Category@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_category' , 'Category@destroyAll');
            Route::delete('category/{id}' , 'Category@destroy');

            /**
             * ******************
             * 关联主体
             * ******************
             */
            Route::get('subject' , 'Subject@index');
            Route::get('subject/{id}' , 'Subject@show');
            Route::patch('subject/{id}' , 'Subject@localUpdate');
            Route::put('subject/{id}' , 'Subject@update');
            Route::post('subject' , 'Subject@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_subject' , 'Subject@destroyAll');
            Route::delete('subject/{id}' , 'Subject@destroy');

            Route::get('search_subject' , 'Subject@search');

            /**
             * ******************
             * 图片主题
             * ******************
             */
            Route::get('image_subject' , 'ImageSubject@index');
            Route::get('image_subject/{id}' , 'ImageSubject@show');
            Route::patch('image_subject/{id}' , 'ImageSubject@localUpdate');
            Route::put('image_subject/{id}' , 'ImageSubject@update');
            Route::post('image_subject' , 'ImageSubject@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_image_subject' , 'ImageSubject@destroyAll');
            Route::delete('image_subject/{id}' , 'ImageSubject@destroy');
            Route::delete('destroy_all_image_for_image_subject' , 'ImageSubject@destroyImages');
            Route::delete('destroy_image_subject_tag' , 'ImageSubject@destroyTag');

            /**
             * ******************
             * 系统位置
             * ******************
             */
            Route::get('position' , 'Position@index');
            Route::get('position/{id}' , 'Position@show');
            Route::patch('position/{id}' , 'Position@localUpdate');
            Route::put('position/{id}' , 'Position@update');
            Route::post('position' , 'Position@store');


            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_position' , 'Position@destroyAll');
            Route::delete('position/{id}' , 'Position@destroy');
            Route::get('search_position' , 'Position@search');
            Route::get('get_all_position' , 'Position@all');

            /**
             * ******************
             * 定点图片
             * ******************
             */
            Route::get('image_at_position' , 'ImageAtPosition@index');
            Route::get('image_at_position/{id}' , 'ImageAtPosition@show');
            Route::patch('image_at_position/{id}' , 'ImageAtPosition@localUpdate');
            Route::put('image_at_position/{id}' , 'ImageAtPosition@update');
            Route::post('image_at_position' , 'ImageAtPosition@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_image_at_position' , 'ImageAtPosition@destroyAll');
            Route::delete('image_at_position/{id}' , 'ImageAtPosition@destroy');

            /**
             * 导航菜单
             */
            Route::get('nav' , 'Nav@index');
            Route::get('nav/{module_id}/get_by_module_id' , 'Nav@getByModuleId');
            Route::get('nav/{id}' , 'Nav@show');
            Route::patch('nav/{id}' , 'Nav@localUpdate');
            Route::put('nav/{id}' , 'Nav@update');
            Route::post('nav' , 'Nav@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_nav' , 'Nav@destroyAll');
            Route::delete('nav/{id}' , 'Nav@destroy');


            /**
             * ******************
             * 视频
             * ******************
             */
            Route::get('video' , 'Video@index');
            Route::get('video/{id}' , 'Video@show');
            Route::patch('video/{id}' , 'Video@localUpdate');
            Route::put('video/{id}' , 'Video@update');
            Route::post('video' , 'Video@store');
            Route::delete('video/{id}' , 'Video@destroy');
            Route::delete('destroy_all_video' , 'Video@destroyAll');
            Route::delete('destroy_videos' , 'Video@destroyVideos');
            Route::post('retry_process_video' , 'Video@retry');

            /**
             * ******************
             * 视频专题
             * ******************
             */
            Route::get('video_subject' , 'VideoSubject@index');
            Route::get('video_subject/{id}' , 'VideoSubject@show');
            Route::patch('video_subject/{id}' , 'VideoSubject@localUpdate');
            Route::put('video_subject/{id}' , 'VideoSubject@update');
            Route::post('video_subject' , 'VideoSubject@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_video_subject' , 'VideoSubject@destroyAll');
            Route::delete('video_subject/{id}' , 'VideoSubject@destroy');
            Route::get('search_video_subject' , 'VideoSubject@search');
            Route::delete('destroy_video_subject_tag' , 'VideoSubject@destroyTag');

            /**
             * ******************
             * 视频系列
             * ******************
             */
            Route::get('video_series' , 'VideoSeries@index');
            Route::get('video_series/{id}' , 'VideoSeries@show');
            Route::patch('video_series/{id}' , 'VideoSeries@localUpdate');
            Route::put('video_series/{id}' , 'VideoSeries@update');
            Route::post('video_series' , 'VideoSeries@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_video_series' , 'VideoSeries@destroyAll');
            Route::delete('video_series/{id}' , 'VideoSeries@destroy');
            Route::get('search_video_series' , 'VideoSeries@search');

            /**
             * region 相关接口
             */
            Route::get('country' , 'Region@country');
            Route::get('search_region' , 'Region@search');

            /**
             * ******************
             * 视频制作公司
             * ******************
             */
            Route::get('video_company' , 'VideoCompany@index');
            Route::get('video_company/{id}' , 'VideoCompany@show');
            Route::patch('video_company/{id}' , 'VideoCompany@localUpdate');
            Route::put('video_company/{id}' , 'VideoCompany@update');
            Route::post('video_company' , 'VideoCompany@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_video_company' , 'VideoCompany@destroyAll');
            Route::delete('video_company/{id}' , 'VideoCompany@destroy');
            Route::get('search_video_company' , 'VideoCompany@search');

            /**
             * 控制台
             */
            Route::get('pannel/info' , 'Pannel@info');

            /**
             * ******************
             * 磁盘管理
             * ******************
             */
            Route::get('disk' , 'Disk@index');
            Route::get('disk/{id}' , 'Disk@show');
            Route::patch('disk/{id}' , 'Disk@localUpdate');
            Route::put('disk/{id}' , 'Disk@update');
            Route::post('disk' , 'Disk@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('destroy_all_disk' , 'Disk@destroyAll');
            Route::delete('disk/{id}' , 'Disk@destroy');

            /***
             * 队列任务相关
             */
            Route::post('retry_job' , 'Job@retry');
            Route::post('flush_job' , 'Job@flush');

            /**
             * 视频字幕相关
             */
            Route::delete('video_subtitle/{id}' , 'VideoSubtitle@destroy');
            Route::delete('destroy_all_video_subtitle' , 'VideoSubtitle@destroyAll');
        });
    });
