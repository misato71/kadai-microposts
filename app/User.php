<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
    *　このユーザーが所有する投稿（Micropostモデルとの関係を定義）
    */
    public function microposts() {
        return $this->hasmany(Micropost::class);
    }
    /**
    *  このユーザーの関係するモデルの件数をロードする
    */
    public function loadRelationshipCounts() {
        $this->loadCount(['microposts', 'followings', 'followers']);
    }
    /**
     *  このユーザーがフォロー中のユーザー（Userモデルとの関係を定義）
     */
    public function followings() {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    /**
     *  このユーザーをフォロー中のユーザー（Userモデルとの関係を定義）
     */
    public function followers() {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    /**
     *  $userIdで指定されたユーザーをフォローする
     */
    public function follow($userId) {
        //  すでにフォローしているか
        $exist = $this->is_following($userId);
        //　対象が自分自身かどうか
        $its_me = $this->id == $userId;
         
        if ($exist || $its_me) {
            //  フォロー済み、または、自分自身の場合は何もしない
            return false;
        } else {
            //  上記以外はフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    /**
     *  $userIdで指定されtaユーザーをアンフォローする
     */
    public function unfollow($userId) {
        //  すでにフォローしているか
        $exist = $this->is_following($userId);
        //  対象が自分自身かどうか
        $its_me = $this->id == $userId;
        
        //  フォロー済み、かつ、自分自身でない場合はフォローを外す
        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            //  上記以外の場合は何もしない
            return false;
        }
    }
    /**
     *  指定された$userIdのユーザーをこのユーザーがフォロー中であるか調べる
     *  フォロー中ならtrueを返す
     */  
    public function is_following($userId) {
        //  フォロー中ユーザーの中に$userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    /**
     *  このユーザーとフォロー中ユーザーの投稿に絞り込む
     */
    public function feed_microposts() {
        //  このユーザーがフォロー中のユーザーのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        //  このユーザーのidもその配列に追加
        $userIds[] = $this->id;
        //  それらのユーザーが所有する投稿を絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
}
