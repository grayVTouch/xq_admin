<?php


use App\Customize\api\web_v1\middleware\CustomizeMiddleware;
use App\Customize\api\web_v1\middleware\UserAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('web_v1')
    ->namespace('api\\web_v1')
    ->middleware([
        CustomizeMiddleware::class
    ])
    ->name('api.web_v1.')
    ->group(function(){
        Route::middleware([

        ])->group(function(){
            // 不用登录的相关接口

            /***
             * *****************
             * 模块相关接口
             * *****************
             */
            Route::get('module' , 'Module@all');
            Route::get('category' , 'Category@all');
            Route::get('nav' , 'Nav@all');
            Route::get('image_at_position/home_slideshow' , 'ImageAtPosition@homeSlideshow');
            Route::get('image_at_position/image_subject' , 'ImageAtPosition@imageSubject');
            Route::get('image_subject/newest' , 'ImageSubject@newest');
            Route::get('image_subject/hot' , 'ImageSubject@hot');
            Route::get('image_subject/newest_with_pager' , 'ImageSubject@newestWithPager');
            Route::get('image_subject/hot_with_pager' , 'ImageSubject@hotWithPager');
            Route::get('image_subject/{tag_id}/get_by_tag_id' , 'ImageSubject@getByTagId');
            Route::get('image_subject/get_with_pager_by_tag_ids' , 'ImageSubject@getWithPagerByTagIds');
            Route::get('image_subject/hot_tags' , 'ImageSubject@hotTags');
            Route::get('image_subject/hot_tags_with_pager' , 'ImageSubject@hotTagsWithPager');
            Route::get('image_subject/{id}' , 'ImageSubject@show');

        });

        Route::middleware([
            UserAuthMiddleware::class
        ])->group(function(){
            // 要求登录的相关接口
            Route::post('image_subject/{image_subject_id}collection_handle' , 'ImageSubjectWithAuth@collectionHandle');
            Route::post('create_collection_group' , 'User@createCollectionGroup');
            Route::post('destroy_collection_group/{id}' , 'User@destroyCollectionGroup');
            Route::post('destroy_all_collection_group' , 'User@destroyAllCollectionGroup');

        });
    });
