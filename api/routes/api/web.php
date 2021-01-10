<?php


use App\Customize\api\web\middleware\CustomizeMiddleware;
use App\Customize\api\web\middleware\UserAuthMiddleware;
use App\Customize\api\web\middleware\UserMiddleware;
use App\Http\Controllers\api\web\Category;
use App\Http\Controllers\api\web\File;
use App\Http\Controllers\api\web\ImageAtPosition;
use App\Http\Controllers\api\web\ImageSubject;
use App\Http\Controllers\api\web\Misc;
use App\Http\Controllers\api\web\Module;
use App\Http\Controllers\api\web\Nav;
use App\Http\Controllers\api\web\Subject;
use App\Http\Controllers\api\web\Tag;
use App\Http\Controllers\api\web\User;
use App\Http\Controllers\api\web\VideoSubject;
use Illuminate\Support\Facades\Route;

Route::prefix('web')
    ->namespace('api\\web')
    ->middleware([
        CustomizeMiddleware::class ,
    ])
    ->name('api.web.')
    ->group(function(){
        Route::middleware([
            UserMiddleware::class
        ])->group(function(){
            // 不用登录的相关接口

            // 文件上传
            Route::post('upload' , [File::class , 'upload']);
            Route::post('upload_image' , [File::class , 'uploadImage']);
            Route::post('upload_video' , [File::class , 'uploadVideo']);
            Route::post('upload_subtitle' , [File::class , 'uploadSubtitle']);
            Route::post('upload_office' , [File::class , 'uploadOffice']);

            /***
             * *****************
             * 模块相关接口
             * *****************
             */
            Route::get('module' , [Module::class , 'all']);
            Route::get('default_module' , [Module::class , 'default']);
            Route::get('category' , [Category::class , 'all']);
            Route::get('nav' , [Nav::class , 'all']);
            Route::get('image_at_position/home_slideshow' , [ImageAtPosition::class , 'homeSlideshow']);
            Route::get('image_at_position/image_subject' , [ImageAtPosition::class , 'imageSubject']);


            /**
             * ****************
             * 图片专题
             * ****************
             */
            Route::get('image_subject/newest' , [ImageSubject::class , 'newest']);
            Route::get('image_subject/hot' , [ImageSubject::class , 'hot']);
            Route::get('image_subject/newest_with_pager' , [ImageSubject::class , 'newestWithPager']);
            Route::get('image_subject/hot_with_pager' , [ImageSubject::class , 'hotWithPager']);
            Route::get('image_subject/{tag_id}/get_by_tag_id' , [ImageSubject::class , 'getByTagId']);
            Route::get('image_subject/get_with_pager_by_tag_ids' , [ImageSubject::class , 'getWithPagerByTagIds']);
            Route::get('image_subject/hot_tags' , [ImageSubject::class , 'hotTags']);
            Route::get('image_subject/hot_tags_with_pager' , [ImageSubject::class , 'hotTagsWithPager']);
            Route::get('image_subject/category' , [ImageSubject::class , 'category']);
            Route::get('image_subject/{image_subject_id}/recommend' , [ImageSubject::class , 'recommend']);

            // 特别注意，下面这个路由仅允许放置到最后一个，否则，符合条件的路由都会被导向到这个路由里面去
            // 这种情况下，就会出现定义的具体路由不生效的情况
            Route::get('image_subject/subject' , [ImageSubject::class , 'subject']);
            Route::get('image_subject/{id}' , [ImageSubject::class , 'show']);
            Route::get('image_project' , [ImageSubject::class , 'index']);


            Route::get('subject/{id}' , [Subject::class , 'show']);
            Route::get('category/{id}' , [Category::class , 'show']);
            Route::get('tag/{id}' , [Tag::class , 'show']);
            Route::get('captcha' , [Misc::class , 'captcha']);

            Route::post('login' , [User::class , 'login']);
            Route::post('register' , [User::class , 'store']);
            Route::patch('user/update_password' , [User::class , 'updatePassword']);
            Route::patch('image_subject/{image_subject_id}/increment_view_count' , [ImageSubject::class , 'incrementViewCount']);

            Route::post('send_email_code_for_password' , [Misc::class , 'sendEmailCodeForPassword']);
            Route::post('send_email_code_for_register' , [Misc::class , 'sendEmailCodeForRegister']);

            Route::get('user/{user_id}/my_focus_user' , [User::class , 'myFocusUser']);
            Route::get('user/{user_id}/focus_me_user' , [User::class , 'focusMeUser']);
            Route::get('user/{user_id}/collection_group' , [User::class , 'collectionGroupByUserId']);
            Route::get('user/{user_id}/show' , [User::class , 'show']);
            Route::get('user/{collection_group_id}/collection_group_info' , [User::class , 'collectionGroupInfo']);
            Route::get('user/collections' , [User::class , 'collections']);

            /**
             * ************************************
             * 视频专题
             * ************************************
             */
            Route::get('/video_project/newest' , [VideoSubject::class , 'newest']);
            Route::get('/video_project/hot_tags' , [VideoSubject::class , 'hotTags']);
            Route::get('/video_project/hot' , [VideoSubject::class , 'hot']);
            Route::get('/video_project/{tag_id}/get_by_tag_id' , [VideoSubject::class , 'getByTagId']);
            Route::get('/video_project/get_by_tag_ids' , [VideoSubject::class , 'getByTagIds']);
            Route::get('/video_project/{id}' , [VideoSubject::class , 'show']);
            Route::get('/video_project/{id}/videos_in_range' , [VideoSubject::class , 'videosInRange']);

            /**
             * 视频系列
             */
            Route::get('/video_project/{id}/video_projects' , [VideoSubject::class , 'videoSubjectsInSeries']);

        });

        Route::middleware([
            UserAuthMiddleware::class
        ])->group(function(){
            // 要求登录的相关接口
            Route::post('user/collection_handle' , [User::class , 'collectionHandle']);
            Route::post('user/praise_handle' , [User::class , 'praiseHandle']);
            Route::post('user/record' , [User::class , 'record']);
            Route::post('user/create_and_join_collection_group' , [User::class , 'createAndJoinCollectionGroup']);
            Route::post('user/create_collection_group' , [User::class , 'createCollectionGroup']);
            Route::post('user/join_collection_group' , [User::class , 'joinCollectionGroup']);
            Route::delete('user/destroy_collection_group' , [User::class , 'destroyCollectionGroup']);
            Route::delete('user/destroy_all_collection_group' , [User::class , 'destroyAllCollectionGroup']);
            Route::delete('user/destroy_collection' , [User::class , 'destroyCollection']);
            Route::delete('user/destroy_history' , [User::class , 'destroyHistory']);
            Route::get('user/collection_group_with_judge' , [User::class , 'collectionGroupWithJudge']);
            Route::get('user/collection_group' , [User::class , 'collectionGroup']);
            Route::get('user_info' , [User::class , 'info']);
            Route::get('less_history' , [User::class , 'lessHistory']);
            Route::get('history' , [User::class , 'histories']);
            Route::get('less_relation_in_collection' , [User::class , 'lessRelationInCollection']);
            Route::get('less_collection_group_with_collection' , [User::class , 'lessCollectionGroupWithCollection']);
            Route::put('update_user' , [User::class , 'update']);
            Route::patch('update_user' , [User::class , 'localUpdate']);
            Route::patch('user/update_password_in_logged' , [User::class , 'updatePasswordInLogged']);
            Route::patch('user/update_collection_group' , [User::class , 'updateCollectionGroup']);

            // 关注用户
            Route::post('user/focus_handle' , [User::class , 'focusHandle']);


        });
    });
