<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = ['title', 'content', 'status', 'private', 'due_date'];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('id', function (Builder $builder) {
            // ログインユーザーのtodoのみに絞る
            // $builder->where('user_id', Auth::id());
        });
    }

    // statusがwaitingのものを降順に並べ替える
    public function scopeLatestTodo($query)
    {
        return $query->where('status', 1)->orderBy('id', 'desc');
    }

    // tasksテーブルのstatusの値からそれぞれの状態を返す
    public function getTodoStatusAttribute()
    {
        if ($this->status === 1) {
            return  'waiting';
        } elseif ($this->status === 2) {
            return 'working';
        } else {
            return  'done';
        }
    }

    // tasksテーブルのprivateの値からそれぞれの状態を返す
    public function getPrivateStatusAttribute()
    {
        if ($this->private === 1) {
            return  'public';
        } else {
            return  'private';
        }
    }

    // フォームから受け取ったprivateの値からDBに格納する値を返す
    public function setPrivateStatusAttribute($value)
    {
        if ($value === 'public') {
            $this->attributes['private'] = 1;
        } else {
            $this->attributes['private'] = 2;
        }
    }
}
