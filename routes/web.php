<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReadLaterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return response()->json(['message' => 'Success']);
});
Route::get('/api/fetch_data', [NewsController::class, 'fetchData']);

Route::post('/create_account',[UserController::class, 'store']);
Route::post('/api/user_login', [UserController::class, 'login']);

Route::get('/api/fetch_newsarticles_data', [NewsController::class, 'fetchNewsArticle']);
Route::get('/api/fetch_guardian_data', [NewsController::class, 'fetchGuardianData']);
Route::get('/api/fetch_nytimes_data', [NewsController::class, 'fetchNYTimesData']);
Route::get('/api/fetchAllNews', [NewsController::class, 'fetchAllNews']);

Route::get('/api/sources', [NewsController::class, 'getSources']);
Route::get('/api/category', [NewsController::class, 'getCategory']);
Route::get('/api/authors', [NewsController::class, 'getAuthors']);

Route::get('/api/filter-news', [NewsController::class, 'filterNews']);
Route::get('/api/filter_news_by_category', [NewsController::class, 'filterNewsByCategory']);
Route::get('/api/filter_news_by_author', [NewsController::class, 'filterNewsByAuthor']);

Route::post('/api/read_later', [ReadLaterController::class, 'saveReadLater']);
Route::get('/api/getSavedReadLater', [ReadLaterController::class, 'getSavedReadLater']);
Route::get('/api/fetchAllSavedArticles', [ReadLaterController::class, 'getSavedArticles']);

