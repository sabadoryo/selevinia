<?php

use App\Http\Controllers\Api\ArchiveController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PublicationsController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Controller;
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

Route::group([
    'prefix' => 'admin'
], function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');

});


Route::resource('categories', CategoryController::class);

Route::resource('posts', PostController::class);
Route::post('/posts/upload-temp-image', [PostController::class, 'uploadTempImage']);
Route::get('/posts/related/{categoryId}', [PostController::class, 'getRelatedPosts']);

Route::resource('archives', ArchiveController::class);
Route::get('archives/{archive}/download-document', [ArchiveController::class, 'downloadDocument']);

Route::resource('publications', PublicationsController::class);
Route::get('publications/{publication}/download-document', [PublicationsController::class, 'downloadDocument']);

Route::resource('authors', AuthorController::class);
Route::post('authors/import/excel', [AuthorController::class, 'uploadAuthorsFromExcel']);

Route::group(['prefix' => 'subscriptions'], function() {
    Route::get('/', [SubscriptionController::class, 'index']);
    Route::post('/', [SubscriptionController::class, 'subscribe']);
    Route::post('send-mailing', [SubscriptionController::class, 'submitMailing']);
});

Route::group(['prefix' => 'pages'], function() {
    Route::get('/', [PagesController::class, 'index']);
    Route::post('/store-pages', [PagesController::class, 'store']);
});

Route::group(['prefix' => 'feedbacks'], function() {
    Route::get('/', [FeedbackController::class, 'index']);
    Route::post('/', [FeedbackController::class, 'store']);
});

Route::get('global-search', [Controller::class, 'globalSearch']);