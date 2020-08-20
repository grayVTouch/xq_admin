<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


require_once __DIR__ . '/api/admin_v1.php';
require_once __DIR__ . '/api/web_v1.php';

Route::get('test/{module_id}/filter' , 'Test@one');
Route::get('test/{p_id}/filter' , 'Test@two');
