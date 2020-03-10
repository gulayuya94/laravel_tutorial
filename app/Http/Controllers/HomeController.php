<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Requests\SearchTodoRequest;
use App\Task;
use App\User;
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
        // ログインユーザの情報を取得
        $user = Auth::user();

        // ログインユーザのタスクの中からidが一番大きいもの(一番新しい)タスクを取得
        $task = $user->tasks()->latestTodo()->first();

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
        // ログインユーザの情報を取得
        $user = Auth::user();

        // ログインユーザの全タスクを取得
        $tasks = $user->tasks()->get();

        return view('todolist', [
            'tasks' => $tasks,
        ]);
    }

    public function showUserList()
    {
        // ログインユーザの情報を取得
        $user = Auth::user();

        // ログインユーザ以外の全ユーザの情報を取得
        $users = User::whereNotIn('id', [$user->id])->get(['name', 'account_name']);

        return view('userlist', [
            'users' => $users,
        ]);
    }

    public function show($account_name)
    {
        // account_nameからuserのidを取得
        $user_id = User::where('account_name', $account_name)->pluck('id');
        $user_name = User::where('id', $user_id)->pluck('name');

        // idに紐づくtodo(公開todo)を取得
        // privateの値が1なら公開、2なら非公開
        $tasks = Task::where('user_id', $user_id)->where('private', 1)->get();

        return view('user', [
            'tasks' => $tasks,
            'user_name' => $user_name,
        ]);

    }

    public function create(CreateTodoRequest $request)
    {
        $newTask = new Task;

        // 情報の受け取り
        $newTask->title = $request->title;
        $newTask->content = $request->content;
        $newTask->due_date = $request->date;
         // setPrivateStatusAttribute でDBに格納する値を判定
        $newTask->private_status = $request->private;
        

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
        // idからtodoの情報を取得
        $task = Task::where('id', $id)->first();

        // データの格納
        $task_data = array();
        $task_data['id'] = $task->id;
        $task_data['title'] = $task->title;
        $task_data['content'] = $task->content;
        $task_data['status'] = $task->todo_status;
        $task_data['private'] = $task->private_status;
        $task_data['due_date'] = $task->due_date;
        
        return view('edit', $task_data);

    }

    public function update(UpdateTodoRequest $request, $id)
    {
        Task::find($id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'private' => $request->private,
            'due_date' => $request->date,
        ]);
            // var_dump((int)$request->private);
        return redirect('/todos/list');
    }

    public function search(SearchTodoRequest $request)
    {
        // ログインユーザの情報を取得
        $user = Auth::user();

        // ログインユーザの全タスクを取得(一覧表示用)
        $tasks = $user->tasks()->get();

        // 検索リクエスト情報の受け取り
        $bufTitle = str_replace(['%', '_'], ['\%', '\_'], $request->title);
        $searchTitle = '%' . $bufTitle . '%';
        $bufContent = str_replace(['%', '_'], ['\%', '\_'], $request->content);
        $searchContent = '%' . $bufContent . '%';
        $searchStatus = $request->status;
        $searchPrivate = $request->private;
        $searchStartDate = $request->startDate;
        $searchEndDate = $request->endDate;

        // 受け取った検索条件を元に該当するtodoを取得
        // 　検索条件に何も入れずに検索した場合はバリデーションで弾くようになっているので、
        // 　コントローラー側ではただ受け取った情報を基に該当するtodoを返す
        $searchResults = $user->tasks()
        ->when($bufTitle, function ($query) use ($searchTitle) {
            return $query->where('title', 'like', $searchTitle);
        })
        ->when($bufContent, function ($query) use ($searchContent) {
            return $query->where('content', 'like', $searchContent);
        })
        ->when($searchStatus, function ($query) use ($searchStatus) {
            return $query->where('status', $searchStatus);
        })
        ->when($searchPrivate, function ($query) use ($searchPrivate) {
            return $query->where('private', $searchPrivate);
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
