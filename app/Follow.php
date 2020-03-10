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
    public function getIsFollowAttribute($value)
    {
        $user = Auth::user();
        return Follow::where('follower_id', $user->id)->where('followee_id', $value)->exists();
    }
}
