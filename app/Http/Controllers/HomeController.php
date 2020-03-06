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
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ログインユーザのタスクの中からidが一番大きいもの(一番新しい)タスクを取得
        $task = Task::latestTodo()->first();

        if (isset($task)) {
            // タスクデータの格納
            $task_data = array();
            $task_data['title'] = $task->title;
            $task_data['content'] = $task->content;
            $task_data['due_date'] = $task->due_date;
            $task_data['id'] = $task->id;

            return view('home', $task_data);

        } else {

            return view('home');
        }
        
    }

    public function showTodoList()
    {
        // ログインユーザの全タスクを取得
        $tasks = Task::get();

        return view('todolist', [
            'tasks' => $tasks,
        ]);

        // echo var_dump($tasks[4]->todo_status);

    }

    public function create(Request $request)
    {
        // インスタンス生成
        $newTask = new Task;

        // 情報の受け取り
        $newTask->title = $request->title;
        $newTask->content = $request->content;
        $newTask->due_date = $request->date;

        $validatedData = $request->validate([
            'title' => 'required|max:40',
            'content' => 'required|max:200',
            'date' => 'required|date|after:yesterday',
        ]);

        // その他情報の格納
        $newTask->user_id = Auth::id();
        $newTask->status = 1;

        // 保存
        $newTask->save();

        // 保存後リダイレクト
        return redirect('/todos/list');

    }

    public function delete($id)
    {
        Task::find($id)->delete();

        // 削除後リダイレクト
        return redirect('/todos/list');

    }

    public function edit($id)
    {
        // idを元にtodoの情報を取得
        $task = Task::where('id', $id)->first();

        // データの格納
        $task_data = array();
        $task_data['id'] = $task->id;
        $task_data['title'] = $task->title;
        $task_data['content'] = $task->content;
        $task_data['status'] = $task->todo_status;
        $task_data['due_date'] = $task->due_date;
        
        // 編集ページに受け渡し
        return view('edit', $task_data);

    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'date' => 'date|after:yesterday',
        ]);

        Task::find($id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'due_date' => $request->date,
        ]);

        // 編集後リダイレクト
        return redirect('/todos/list');
    }

    public function search(Request $request)
    {
        // ログインユーザの全タスクを取得(一覧表示用)
        $tasks = Task::get();

        // 検索リクエスト情報の受け取り
        $bufTitle = $request->title;
        $searchTitle = '%' . $bufTitle . '%';
        $bufContent = $request->content;
        $searchContent = '%' . $bufContent . '%';
        $searchStatus = $request->status;
        $searchStartDate = $request->startDate;
        $searchEndDate = $request->endDate;

        $validatedData = $request->validate([
            'title' => 'required_without_all:content,status,startDate,endDate',
            'content' => '',
            'status' => '',
            'startDate' => '',
            'endDate' => '',
        ]);

        $searchResults = Task::
        when($bufTitle, function ($query) use ($searchTitle) {
            return $query->where('title', 'like', $searchTitle);
        })
        ->when($bufContent, function ($query) use ($searchContent) {
            return $query->where('content', 'like', $searchContent);
        })
        ->when($searchStatus, function ($query) use ($searchStatus) {
            return $query->where('status', $searchStatus);
        })
        ->when($searchStartDate, function ($query) use ($searchStartDate) {
            return $query->where('due_date', '>=', $searchStartDate);
        })
        ->when($searchEndDate, function ($query) use ($searchEndDate) {
            return $query->where('due_date', '<=', $searchEndDate);
        })
        ->get();
        
        return view('todoList', [
            'searchResults' => $searchResults,
            'tasks' => $tasks,
        ]);
    }
}
