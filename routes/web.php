<?php

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

// ルートにアクセスでログイン前画面を表示する
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// topページ
Route::get('/home', 'HomeController@index')->name('home');

// task一覧ページ
Route::get('/tasklist', 'HomeController@showTaskList')->name('tasklist');

// todo作成ページ
Route::get('/create', function () {
    return view('create');
});

// todo作成リクエスト
Route::post('/create', 'HomeController@create')->name('create');
