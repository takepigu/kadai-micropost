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
     * このユーザーが所有する投稿。（Micropostモデルとの関係を定義）
     */
     public function microposts()
     {
         return $this->hasMany(Micropost::class);
     }
    
    /**
     * このユーザーに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCount()
     { 
         $this->loadCount('microposts','followings','followers');
     }
    
    /**
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    /**
     * $userIdで指定されたユーザーをフォローする。
     * 
     * @param int $userId
     * @return bool
     */
     public function follow($userId)
     {
         //すでにフォローしているかの確認
         $exist = $this->is_following($userId);
         //対象が自分自身かどうかの確認
         $its_me = $this->id == $userId;
         
         if ($exist || $its_me) {
             //既にフォローしていれば何もしない
             return false;
         }else {
             //未フォローであればフォローする
             $this->followings()->attach($userId);
             return true;
         }
     }
     
     /**
      * $userIdで指定されたユーザーをあんフォローする。
      * 
      * @param int $userId
      * @return bool
      */
      public function unfollow($userId)
      {
          //すでにフォローしているかの確認
          $exist = $this->is_following($userId);
          //対象が自分自身かどうかの確認
          $its_me = $this->id == $userId;
          
          if ($exist && !$its_me) {
              //既にフォローしていればフォローを外す
              $this->followings()->detach($userId);
              return true;
          } else {
              //未フォローであれば何もしない
              return false;
          }
      }
      /**
       * 指定された$userIdのユーザーをこのユーザーがフォロー中であるか調べる。フォロー中ならtrueを返す。
       * 
       * @param int $userId
       * @return bool
       */
       public function is_following($userId)
       {
           //フォロー中ならユーザーの中に$userIdのものが存在するか
           return $this->followings()->where('follow_id',$userId)->exists();
       }
       
       /**
        * このユーザーとフォロー中ユーザーの投稿に絞り込む。
        */
        public function feed_microposts()
        {
            //ユーザーがフォロー中のユーザーのidを取得して配列にする
            $userId = $this->followings()->pluck('users.id')->toArray();
            //ユーザーのidもその配列に追加
            $userIds[] = $this->id;
            //それらのユーザーが所有する投稿に絞り込む
            return Micropost::whereIn('user_id',$userIds);
        }
}

