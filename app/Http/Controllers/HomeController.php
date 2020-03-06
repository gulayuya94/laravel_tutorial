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
        // ログインユーザ情報を取得
        $user = Auth::user();

        // ログインユーザのタスクの中からidが一番大きいもの(一番新しい)タスクを取得
        $task = $user->tasks->where('status', 1)->sortByDesc('id')->first();

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
        // ログインユーザ情報を取得
        $user = Auth::user();

        // ログインユーザの全タスクを取得
        $tasks = $user->tasks;


        foreach ($tasks as $task) {
            if ($task['status'] === 1) {
                $task['status'] = 'waiting';
            } elseif ($task['status'] === 2) {
                $task['status'] = 'working';
            } else {
                $task['status'] = 'done';
            }
        }

        return view('todolist', [
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
        // ログインユーザ情報を取得
        $user = Auth::user();

        // idを元にtodoの情報を取得
        $task = $user->tasks->where('id', $id)->first();

        // データの格納
        $task_data = array();
        $task_data['id'] = $task->id;
        $task_data['title'] = $task->title;
        $task_data['content'] = $task->content;
        $task_data['status'] = $task->status;
        $task_data['due_date'] = $task->due_date;
        
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
        return redirect('/todos/list');
    }

    public function search(Request $request)
    {
        // ログインユーザ情報を取得
        $user = Auth::user();

        // ログインユーザの全タスクを取得
        // todo一覧表示用
        $tasks = $user->tasks;

        foreach ($tasks as $task) {
            if ($task['status'] === 1) {
                $task['status'] = 'waiting';
            } elseif ($task['status'] === 2) {
                $task['status'] = 'working';
            } else {
                $task['status'] = 'done';
            }
        }

        // 検索リクエスト情報の受け取り
        $bufTitle = $request->title;
        $searchTitle = '%' . $bufTitle . '%';
        $bufContent = $request->content;
        $searchContent = '%' . $bufContent . '%';
        $searchStatus = $request->status;
        $searchStartDate = $request->startDate;
        $searchEndDate = $request->endDate;

        // 単独検索判定用
        $judge = array();

        // 複数条件判定用
        $judges = 0;

        // 検索リクエストを元にtasksテーブルから該当する行を取得する
        if (!is_null($bufTitle)) {
            $resSearchTitle = $user->tasks()->where('title', 'like', $searchTitle)->get();

            $bufTitleId = array();

            foreach ($resSearchTitle as $item) {
                array_push($bufTitleId, $item->id);
            }

            array_push($judge, 1);

            $judges += 1;
            

        } else {
            $bufTitleId = array();
        }

        if (!is_null($bufContent)) {
            $resSearchContent = $user->tasks()->where('content', 'like', $searchContent)->get();

            $bufContentId = array();

            foreach ($resSearchContent as $item) {
                array_push($bufContentId, $item->id);
            }

            array_push($judge, 2);

            $judges += 10;

        } else {
            $bufContentId = array();
        }

        if (!is_null($searchStatus)) {
            $resSearchStatus = $user->tasks()->where('status', $searchStatus)->get();

            $bufStatusId = array();

            foreach ($resSearchStatus as $item) {
                array_push($bufStatusId, $item->id);
            }

            array_push($judge, 3);

            $judges += 100;

        } else {
            $bufStatusId = array();
        }

        if (!is_null($searchStartDate) and !is_null($searchEndDate)) {
            $resSearchDate = $user->tasks()->whereBetween('due_date', [$searchStartDate, $searchEndDate])->get();

            $bufDateId = array();

            foreach ($resSearchDate as $item) {
                array_push($bufDateId, $item->id);
            }

            array_push($judge, 4);

            $judges += 1000;

        } elseif (!is_null($searchStartDate) and is_null($searchEndDate)) {
            $resSearchDate = $user->tasks()->where('due_date', '>=', $searchStartDate)->get();

            $bufDateId = array();

            foreach ($resSearchDate as $item) {
                array_push($bufDateId, $item->id);
            }

            array_push($judge, 4);

            $judges += 1000;

        } elseif (is_null($searchStartDate) and !is_null($searchEndDate)) {
            $resSearchDate = $user->tasks()->where('due_date', '<=', $searchEndDate)->get();

            $bufDateId = array();

            foreach ($resSearchDate as $item) {
                array_push($bufDateId, $item->id);
            }

            array_push($judge, 4);

            $judges += 1000;

        } else {
            $bufDateId = array();
        }

        // 取得した行の中からtaskのidが共通するものだけを検索結果としてviewに渡す

        $counter = count($judge);

        if ($counter === 1) {
            // 単一条件検索の場合は$judgeの数字から検索条件の種類を特定して、
            // $buf~idのidから再度tasksテーブルを検索して対応するidの行を取得
            // 取得結果をviewに渡す

            switch ($judge[0]) {
                case 1:
                    $searchResults = $user->tasks()->whereIn('id', $bufTitleId)->get();
                    break;

                case 2:
                    $searchResults = $user->tasks()->whereIn('id', $bufContentId)->get();
                    break;

                case 3:
                    $searchResults = $user->tasks()->whereIn('id', $bufStatusId)->get();
                    break;

                case 4:
                    // $searchResults = DB::table('tasks')->whereIn('id', $bufDateId)->get();
                    $searchResults = $user->tasks()->whereIn('id', $bufDateId)->get();
                    break;
            }

        } elseif ($counter > 1) {
            // 複数条件検索の場合は共通idだけ抽出

            switch ($judges) {
                case 11:
                    $targetId = array_intersect($bufTitleId, $bufContentId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 101:
                    $targetId = array_intersect($bufTitleId, $bufStatusId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 110:
                    $targetId = array_intersect($bufContentId, $bufStatusId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 111:
                    $targetId = array_intersect($bufTitleId, $bufContentId, $bufStatusId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 1001:
                    $targetId = array_intersect($bufTitleId, $bufDateId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 1010:
                    $targetId = array_intersect($bufContentId, $bufDateId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 1011:
                    $targetId = array_intersect($bufTitleId, $bufContentId, $bufDateId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 1100:
                    $targetId = array_intersect($bufStatusId, $bufDateId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 1101:
                    $targetId = array_intersect($bufTitleId, $bufStatusId, $bufDateId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 1110:
                    $targetId = array_intersect($bufContentId, $bufStatusId, $bufDateId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;

                case 1111:
                    $targetId = array_intersect($bufTitleId, $bufContentId, $bufStatusId, $bufDateId);
                    $searchResults = $user->tasks()->whereIn('id', $targetId)->get();
                    break;
            }
        } else {
            // 0の場合(何も入れずに検索を実行)は、メッセージを返す
            $noResult = '';
        }

        // 検索結果を返す
        if (isset($searchResults)) {

            foreach ($searchResults as $item) {
                if ($item['status'] === 1) {
                    $item['status'] = 'waiting';
                } elseif ($item['status'] === 2) {
                    $item['status'] = 'working';
                } else {
                    $item['status'] = 'done';
                }
            }

            return view('todoList', [
                'searchResults' => $searchResults,
                'tasks' => $tasks,
            ]);
        } else {
            return view('todoList', [
                'noResult' => $noResult,
                'tasks' => $tasks,
            ]);
        }

    }
}
