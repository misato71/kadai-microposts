<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content'];
    /**
     *　この投稿を所有するユーザー。（Userモデルとの関係を定義）
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
    /**
     * この投稿をお気に入りにしたユーザー。（Micropostモデルとの関係を定義）
     */
    public function favorite_users() {
        return $this->belongsToMany(Micropost::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }
}
