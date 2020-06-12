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
            Route::get('admin_permission' , 'AdminPermission@index');
            Route::get('admin_permission/{id}' , 'AdminPermission@show');
            Route::patch('admin_permission/{id}' , 'AdminPermission@localUpdate');
            Route::put('admin_permission/{id}' , 'AdminPermission@update');
            Route::post('admin_permission' , 'AdminPermission@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('admin_permission/destroy_all' , 'AdminPermission@destroyAll');
            Route::delete('admin_permission/{id}' , 'AdminPermission@destroy');

            /**
             * ******************
             * 角色管理
             * ******************
             */
            Route::get('role' , 'Role@index');
            Route::get('role/{id}' , 'Role@show');
            Route::patch('role/{id}' , 'Role@localUpdate');
            Route::put('role/{id}' , 'Role@update');
            Route::post('role' , 'Role@store');
            // 特别注意，这边这个顺序不能更换
            // 如果更换会导致 路由匹配出现不是期望的现象
            Route::delete('role/destroy_all' , 'Role@destroyAll');
            Route::delete('role/{id}' , 'Role@destroy');


        });
    });
