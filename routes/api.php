<?php

use App\Book;
use App\Http\Controllers\BookController;
use App\Jobs\SendMail;
use App\Mail\ActionDetailMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

Route::group(['prefix' => 'authentication'], function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get("/logout",'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api','scope:do_anything,can_create'], function () {
    Route::apiresource('book', 'BookController');
    Route::get('/books/permenentDelete/{id}', 'BookController@PermenentDelete');
    Route::get('/books/restore/{id}','BookController@restore');
    Route::get('/books/allSoftDeleteBook','BookController@softDeleted');
    Route::get('/books/allBooksWithDelete','BookController@booksWithSoftDelete');
});
