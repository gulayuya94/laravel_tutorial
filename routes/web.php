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

Route::group(['middleware' => 'auth'], function() {
    // topページ
    Route::get('/todos', 'HomeController@index')->name('top');

    // todo一覧ページ
    Route::get('/todos/list', 'HomeController@showTodoList')->name('todoList');

    // user一覧ページ
    Route::get('/todos/userlist', 'HomeController@showUserList')->name('userList');

    // user詳細ページ
    Route::get('/todos/user/{account_name}', 'HomeController@show')->name('show');

    // todo作成ページ
    Route::get('/todos/create', function () {
        return view('create');
    });

    // todo作成リクエスト
    Route::post('/todos/create', 'HomeController@create')->name('create');

    // todo詳細ページ
    Route::get('/todos/edit/{id}', 'HomeController@edit')->name('edit');

    // todo更新リクエスト
    Route::put('/todos/{id}', 'HomeController@update')->name('update');

    // todo削除リクエスト
    Route::delete('/todos/{id}', 'HomeController@delete')->name('delete');

    // todo検索リクエスト
    Route::get('/todos/search', 'HomeController@search')->name('search');

    // userフォローリクエスト
    Route::post('/todos/follow/{account_name}', 'HomeController@follow')->name('follow');

    // userアンフォローリクエスト
    Route::delete('/todos/unfollow/{account_name}', 'HomeController@unfollow')->name('unfollow');

});

// ルートにアクセスでログイン前画面を表示する
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
