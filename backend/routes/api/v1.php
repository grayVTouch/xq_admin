<?php

use App\Customize\api\v1\middleware\CustomizeMiddleware;
use App\Customize\api\v1\middleware\UserAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->namespace('api\v1\\')
    ->middleware([
        CustomizeMiddleware::class
    ])
    ->name('api.v1.')
    ->group(function(){
        Route::middleware([])
            ->group(function(){
                // 不用登录的相关接口
                Route::get('captcha' , 'Misc@captcha');
                Route::post('login' , 'Login@login');
                Route::get('avatar' , 'Login@avatar');
                Route::get('test' , 'Test@index');

                Route::post('upload' , 'File@upload');
            });

        Route::middleware([
            UserAuthMiddleware::class
        ])->group(function(){
            // 要求登录的相关接口
            Route::get('info' , 'User@info');

            /**
             * 权限管理
             */
            Route::get('permission' , 'AdminPermission@index');
            Route::get('permission/{id}' , 'AdminPermission@show');
            Route::patch('permission/{id}' , 'AdminPermission@localUpdate');
            Route::put('permission/{id}' , 'AdminPermission@update');
            Route::post('permission' , 'AdminPermission@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('permission/destroy_all' , 'AdminPermission@destroyAll');
            Route::delete('permission/{id}' , 'AdminPermission@destroy');


        });
    });
