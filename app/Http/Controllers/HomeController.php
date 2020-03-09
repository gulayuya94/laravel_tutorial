<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ValiTodoRequest;
use App\Http\Requests\SearchTodoRequest;
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

        // ログインユーザに該当するtodoがあれば、
        if (isset($task)) {
            // タスクデータの格納
            $task_data = array();
            $task_data['title'] = $task->title;
            $task_data['content'] = $task->content;
            $task_data['due_date'] = $task->due_date;
            $task_data['id'] = $task->id;

            return view('home', $task_data);

        } else {

            // 該当するtodoがない場合はtodoのデータを渡さずtop画面を表示
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

    }

    public function create(ValiTodoRequest $request)
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

        return redirect('/todos/list');

    }

    public function delete($id)
    {
        Task::find($id)->delete();

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
        
        return view('edit', $task_data);

    }

    public function update(ValiTodoRequest $request, $id)
    {
        Task::find($id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'due_date' => $request->date,
        ]);

        return redirect('/todos/list');
    }

    public function search(SearchTodoRequest $request)
    {
        // ログインユーザの全タスクを取得(一覧表示用)
        $tasks = Task::get();

        // 検索リクエスト情報の受け取り
        $bufTitle = str_replace(['%', '_'], ['\%', '\_'], $request->title);
        $searchTitle = '%' . $bufTitle . '%';
        $bufContent = str_replace(['%', '_'], ['\%', '\_'], $request->content);
        $searchContent = '%' . $bufContent . '%';
        $searchStatus = $request->status;
        $searchStartDate = $request->startDate;
        $searchEndDate = $request->endDate;

        // 受け取った検索条件を元に該当するtodoを取得
        // 　検索条件に何も入れずに検索した場合はバリデーションで弾くようになっているので、
        // 　コントローラー側ではただ受け取った情報を基に該当するtodoを返す
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
