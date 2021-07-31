<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    /**
     * ユーザーをフォローするアクション。
     * 
     * @param $id 相手ユーザーのid
     * @return \Illuminate\Http\Response
     */
     public function store($id) 
     {
         //認証済みユーザー（閲覧者）が、idのユーザーをフォローする
         \Auth::user()->follow($id);
         //前のURLへリダイレクトさせる
         return back();
     }
    
    /**
     * ユーザーをアンフォローするアクション。
     * 
     * @param $id 相手ユーザーのid
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        //認証済みユーザー（閲覧者）が、idのユーザーをアンフォローする
        \Auth::user()->unfollow($id);
        //前のURLへリダイレクトさせる
        return back();
    }
}
