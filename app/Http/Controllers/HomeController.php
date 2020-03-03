<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Task;
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

        // タスクデータの格納
        $task_data = array();
        $task_data['title'] = $tasks->title;
        $task_data['content'] = $tasks->content;
        $task_data['due_date'] = $tasks->due_date;
        $task_data['id'] = $tasks->id;


        return view('home', $task_data);
    }

    public function showTaskList()
    {
        // ログインユーザのidを取得
        $auth_user_id = Auth::id();

        // ログインユーザの全タスクを取得
        $tasks = DB::table('tasks')->where('user_id', $auth_user_id)->get();

        return view('tasklist', [
            'tasks' => $tasks,
        ]);
    }

    public function create(Request $request)
    {
        // インスタンス生成
        $newTask = new Task;

        // 情報の受け取り
        $newTask->title = $request->title;
        $newTask->content = $request->content;
        $newTask->due_date = $request->date;

        // その他情報の格納
        $newTask->user_id = Auth::id();
        $newTask->status = 1;

        // 保存
        $newTask->save();

        // 保存後リダイレクト
        return redirect('/tasklist');

    }

    public function delete($id)
    {
        Task::find($id)->delete();

        // 削除後リダイレクト
        return redirect('/tasklist');

    }

    public function edit($id)
    {
        // idを元にtodoの情報を取得
        $tasks = DB::table('tasks')->where('id', $id)->first();

        // データの格納
        $task_data = array();
        $task_data['id'] = $tasks->id;
        $task_data['title'] = $tasks->title;
        $task_data['content'] = $tasks->content;
        $task_data['status'] = $tasks->status;
        $task_data['due_date'] = $tasks->due_date;
        
        // 編集ページに受け渡し
        return view('edit', $task_data);

    }

    public function update(Request $request, $id)
    {
        Task::find($id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'due_date' => $request->date,
        ]);

        // 編集後リダイレクト
        return redirect('/tasklist');
    }
}
