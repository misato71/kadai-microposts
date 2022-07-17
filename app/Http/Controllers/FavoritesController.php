<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //  投稿をお気に入りにするアクション
    public function store($id) {
        //  認証済みユーザーが、idの投稿をお気に入りにする
        \Auth::user()->favorite($id);
        
        return back();
    }
    
    //  投稿をお気に入りから外すアクション
    public function destroy($id) {
        //  認証済みユーザーが、idの投稿をお気に入りから外す
        \Auth::user()->unfavorite($id);
        
        return back();
    }
}
