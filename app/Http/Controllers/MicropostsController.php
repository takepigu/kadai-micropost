<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MicropostsController extends Controller
{
    public function index() 
    {
        $data=[];
        if (\Auth::check()) { //認証済みの場合
        //認証済みユーザーを取得
        $user=\Auth::user();
        //ユーザーの投稿の一覧を作成日時の降順で取得
        //（後のChapterで他ユーザーの投稿も取得するように変更しますが、現時点ではこのユーザーの投稿のみ取得します）
        $microposts=$user->microposts()->orderBy('created_at','desc')->paginate(10);
        
        $data=[
            'user' =>$user,
            'microposts'=>$microposts,
            ];
        }
    }
    
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'content' => 'required|max:255',
            ]);
            
            //前のURLへリダイレクトさせる
            return back();
    }
    
    public function destroy($id)
    {
        //idの値で投稿を検索して取得
        $micropost =\App\Micropost::findOrFail($id);
        
        //認証済みユーザー（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() ===$micropost->user_id) {
            $micropost->delete();
        }
        //前のURLへリダイレクトさせる
        return back();
    }
}