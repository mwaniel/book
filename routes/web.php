<?php

use App\Book;
use App\Mail\ActionDetailMail;
use App\User;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get("/email",function(){
    $action = "created";
    $book = Book::find(1);
    $user = User::find($book->user_id);
    return new ActionDetailMail($book,$user,$action);
});
