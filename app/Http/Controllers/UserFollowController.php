<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    //  ユーザーをフォローするアクション
    public function store($id) {
        //  認証済みユーザーが、idのユーザーをフォローする
        \Auth::user()->follow($id);
        
        return back();
    }
    
    //  ユーザーをアンフォローするアクション
    public function destroy($id) {
        //  認証済みユーザーが、idのユーザーをアンフォローする
        \Auth::user()->unfollow($id);
        
        return back();
    }
}
