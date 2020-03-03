<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ログインユーザのidを取得
        $auth_user_id = Auth::id();

        // ログインユーザのタスクの中からidが一番大きいもの(一番新しい)タスクを取得
        $tasks = DB::table('tasks')->where('user_id', $auth_user_id)->where('status', 1)->orderBy('id', 'desc')->first();

        // 全タスクを取得してそれぞれに処理をする場合
        // foreach ($tasks as $task)
        // {
        //     if ($task->user_id === Auth::id()) {
        //         echo $task->title;
        //     }
        // }

        // echo $tasks->title;

        // タスクデータの格納
        $task_data = array();
        $task_data['title'] = $tasks->title;
        $task_data['content'] = $tasks->content;
        $task_data['status'] = $tasks->status;


        return view('home', $task_data);
    }
}
