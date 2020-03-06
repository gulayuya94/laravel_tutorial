<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = ['title', 'content', 'status', 'due_date'];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('id', function (Builder $builder) {
            // ログインユーザーのtodoのみ
            // $builder->where('user_id', auth()->id());
            $builder->where('user_id', Auth::id());
        });
    }

    // ログインユーザのtodoで一番新しいwaitingのtodoを一件取得
    public function scopeNewTodo($query)
    {
        return $query->where('status', 1)->orderBy('id', 'desc')->first();
    }

    public function getTodoStatusAttribute()
    {
        if ($this->status === 1) {
            return $this->status = 'waiting';
        } elseif ($this->status === 2) {
            return $this->status = 'working';
        } else {
            return $this->status = 'done';
        }
    }


    
}
