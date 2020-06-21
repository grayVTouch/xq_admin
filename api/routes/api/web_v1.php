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
            Route::get('get_all_module' , 'Module@all');
            Route::get('get_all_category' , 'Category@all');
        });

        Route::middleware([
            UserAuthMiddleware::class
        ])->group(function(){
            // 要求登录的相关接口

        });
    });
