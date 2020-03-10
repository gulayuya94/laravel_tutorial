<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Follow extends Model
{
    protected $fillable = [
        'follower_id', 'followee_id',
    ];

    // そのユーザのことをログインユーザがフォローしているか返す
    public function getIsFollowAttribute()
    {
        $login_user = Auth::user();
        return Follow::where('follower_id', $login_user->id)->where('followee_id', $this->id)->exists();
    }
}
