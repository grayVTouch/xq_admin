<?php


use App\Customize\api\web_v1\middleware\CustomizeMiddleware;
use App\Customize\api\web_v1\middleware\UserAuthMiddleware;
use App\Customize\api\web_v1\middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('web_v1')
    ->namespace('api\\web_v1')
    ->middleware([
        CustomizeMiddleware::class
    ])
    ->name('api.web_v1.')
    ->group(function(){
        Route::middleware([
            UserMiddleware::class
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
            Route::get('image_subject/category' , 'ImageSubject@category');

            Route::get('image_subject/{image_subject_id}/recommend' , 'ImageSubject@recommend');

            // 特别注意，下面这个路由仅允许放置到最后一个，否则，符合条件的路由都会被导向到这个路由里面去
            // 这种情况下，就会出现定义的具体路由不生效的情况
            Route::get('image_subject/subject' , 'ImageSubject@subject');
            Route::get('image_subject/{id}' , 'ImageSubject@show');
            Route::get('image_subject' , 'ImageSubject@index');

            Route::get('subject/{id}' , 'Subject@show');
            Route::get('category/{id}' , 'Category@show');
            Route::get('tag/{id}' , 'Tag@show');
            Route::get('captcha' , 'Misc@captcha');

            Route::post('login' , 'User@login');
            Route::post('register' , 'User@store');
            Route::patch('user/update_password' , 'User@updatePassword');
            Route::patch('image_subject/{image_subject_id}/increment_view_count' , 'ImageSubject@incrementViewCount');
            // 文件上传
            Route::post('upload' , 'File@upload');

            Route::post('send_email_code_for_password' , 'Misc@sendEmailCodeForPassword');
            Route::post('send_email_code_for_register' , 'Misc@sendEmailCodeForRegister');
        });

        Route::middleware([
            UserAuthMiddleware::class
        ])->group(function(){
            // 要求登录的相关接口
            Route::post('user/collection_handle' , 'User@collectionHandle');
            Route::post('user/praise_handle' , 'User@praiseHandle');
            Route::post('user/record' , 'User@record');
            Route::post('user/create_and_join_collection_group' , 'User@createAndJoinCollectionGroup');
            Route::post('user/create_collection_group' , 'User@createCollectionGroup');
            Route::post('user/join_collection_group' , 'User@joinCollectionGroup');
            Route::delete('user/destroy_collection_group' , 'User@destroyCollectionGroup');
            Route::delete('user/destroy_all_collection_group' , 'User@destroyAllCollectionGroup');
            Route::delete('user/destroy_collection' , 'User@destroyCollection');
            Route::delete('user/destroy_history' , 'User@destroyHistory');
            Route::get('user/collection_group_with_judge' , 'User@collectionGroupWithJudge');
            Route::get('user/collection_group' , 'User@collectionGroup');
            Route::get('user/collections' , 'User@collections');
            Route::get('user_info' , 'User@info');
            Route::get('less_history' , 'User@lessHistory');
            Route::get('history' , 'User@histories');
            Route::get('less_relation_in_collection' , 'User@lessRelationInCollection');
            Route::get('less_collection_group_with_collection' , 'User@lessCollectionGroupWithCollection');
            Route::put('user/update' , 'User@update');
            Route::patch('user/update_password_in_logged' , 'User@updatePasswordInLogged');
            Route::patch('user/update_collection_group' , 'User@updateCollectionGroup');


        });
    });
