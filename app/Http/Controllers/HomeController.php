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
        // ログインユーザのidを取得
        $auth_user_id = Auth::id();

        // ログインユーザのタスクの中からidが一番大きいもの(一番新しい)タスクを取得
        $tasks = DB::table('tasks')->where('user_id', $auth_user_id)->where('status', 1)->orderBy('id', 'desc')->first();

        if (isset($tasks)) {
            // タスクデータの格納
            $task_data = array();
            $task_data['title'] = $tasks->title;
            $task_data['content'] = $tasks->content;
            $task_data['due_date'] = $tasks->due_date;
            $task_data['id'] = $tasks->id;

            return view('home', $task_data);

        } else {

            return view('home');
        }
        
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

    public function search(Request $request)
    {
        // ログインユーザのid取得
        $auth_user_id = Auth::id();

        // ログインユーザの全タスクを取得
        // todo一覧表示用
        $tasks = DB::table('tasks')->where('user_id', $auth_user_id)->get();

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
            $resSearchTitle = DB::table('tasks')->where('user_id', $auth_user_id)->where('title', 'like', $searchTitle)->get();

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
            $resSearchContent = DB::table('tasks')->where('user_id', $auth_user_id)->where('content', 'like', $searchContent)->get();

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
            $resSearchStatus = DB::table('tasks')->where('user_id', $auth_user_id)->where('status', $searchStatus)->get();

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
            $resSearchDate = DB::table('tasks')->where('user_id', $auth_user_id)->whereBetween('due_date', [$searchStartDate, $searchEndDate])->get();

            $bufDateId = array();

            foreach ($resSearchDate as $item) {
                array_push($bufDateId, $item->id);
            }

            array_push($judge, 4);

            $judges += 1000;

        } elseif (!is_null($searchStartDate) and is_null($searchEndDate)) {
            $resSearchDate = DB::table('tasks')->where('user_id', $auth_user_id)->where('due_date', '>=', $searchStartDate)->get();

            $bufDateId = array();

            foreach ($resSearchDate as $item) {
                array_push($bufDateId, $item->id);
            }

            array_push($judge, 4);

            $judges += 1000;

        } elseif (is_null($searchStartDate) and !is_null($searchEndDate)) {
            $resSearchDate = DB::table('tasks')->where('user_id', $auth_user_id)->where('due_date', '<=', $searchEndDate)->get();

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
                    $searchResults = DB::table('tasks')->whereIn('id', $bufTitleId)->get();
                    break;

                case 2:
                    $searchResults = DB::table('tasks')->whereIn('id', $bufContentId)->get();
                    break;

                case 3:
                    $searchResults = DB::table('tasks')->whereIn('id', $bufStatusId)->get();
                    break;

                case 4:
                    $searchResults = DB::table('tasks')->whereIn('id', $bufDateId)->get();
                    break;
            }

        } elseif ($counter > 1) {
            // 複数条件検索の場合は共通idだけ抽出

            switch ($judges) {
                case 11:
                    $targetId = array_intersect($bufTitleId, $bufContentId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 101:
                    $targetId = array_intersect($bufTitleId, $bufStatusId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 110:
                    $targetId = array_intersect($bufContentId, $bufStatusId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 111:
                    $targetId = array_intersect($bufTitleId, $bufContentId, $bufStatusId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 1001:
                    $targetId = array_intersect($bufTitleId, $bufDateId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 1010:
                    $targetId = array_intersect($bufContentId, $bufDateId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 1011:
                    $targetId = array_intersect($bufTitleId, $bufContentId, $bufDateId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 1100:
                    $targetId = array_intersect($bufStatusId, $bufDateId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 1101:
                    $targetId = array_intersect($bufTitleId, $bufStatusId, $bufDateId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 1110:
                    $targetId = array_intersect($bufContentId, $bufStatusId, $bufDateId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;

                case 1111:
                    $targetId = array_intersect($bufTitleId, $bufContentId, $bufStatusId, $bufDateId);
                    $searchResults = DB::table('tasks')->whereIn('id', $targetId)->get();
                    break;
            }
        } else {
            // 0の場合(何も入れずに検索を実行)は、メッセージを返す
            $noResult = '';
        }

        // 検索結果を返す
        if (isset($searchResults)) {
            return view('tasklist', [
                'searchResults' => $searchResults,
                'tasks' => $tasks,
            ]);
        } else {
            return view('tasklist', [
                'noResult' => $noResult,
                'tasks' => $tasks,
            ]);
        }
    }
}
