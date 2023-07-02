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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/register', [\App\Http\Controllers\Admin\UserController::class, 'getUser']);
Route::post('/register', [\App\Http\Controllers\Admin\LoginController::class, 'register']);

Route::post('/login', [\App\Http\Controllers\Admin\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout']);
Route::post('/sent_email', [\App\Http\Controllers\Admin\LoginController::class, 'sendEmail']);
//商品类型
Route::post('/goods_category', [\App\Http\Controllers\Admin\GoodsCategoryController::class, 'goodsCategoryAction'])->middleware('information');
Route::any('/goods_category', [\App\Http\Controllers\Admin\GoodsCategoryController::class, 'goodsCategoryAction'])->middleware('information');
Route::get('/goods_category', [\App\Http\Controllers\Admin\GoodsCategoryController::class, 'goodsCategoryList'])->middleware('information');
Route::delete('/goods_category', [\App\Http\Controllers\Admin\GoodsCategoryController::class, 'goodsCategoryDelete'])->middleware('information');


Route::get('/user', [\App\Http\Controllers\Admin\UserController::class, 'getUser'])->middleware('information');
Route::post('/user', [\App\Http\Controllers\Admin\UserController::class, 'userAction'])->middleware('information');
Route::put('/user', [\App\Http\Controllers\Admin\UserController::class, 'userAction'])->middleware('information');
Route::delete('/user', [\App\Http\Controllers\Admin\UserController::class, 'userDelete'])->middleware('information');


//获取菜单全部
Route::get('/get_menu', [\App\Http\Controllers\Admin\MenuController::class, 'getMenu'])->middleware('information');
//获取菜单全部
Route::post('/user_role', [\App\Http\Controllers\Admin\UserRoleController::class, 'userRoleAction'])->middleware('information');
Route::put('/user_role', [\App\Http\Controllers\Admin\UserRoleController::class, 'userRoleAction'])->middleware('information');
Route::get('/user_role', [\App\Http\Controllers\Admin\UserRoleController::class, 'userRoleList'])->middleware('information');
Route::delete('/user_role', [\App\Http\Controllers\Admin\UserRoleController::class, 'userRoleDelete'])->middleware('information');

