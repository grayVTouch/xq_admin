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
            });

        Route::middleware([
            UserAuthMiddleware::class
        ])->group(function(){
            // 要求登录的相关接口
            Route::get('info' , 'User@info');
        });
    });
