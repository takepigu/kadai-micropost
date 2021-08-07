{{--
自分の投稿もお気に入りに入れられるようにしましょう。
お気に入りのルート名は正しいでしょうか？web.phpを確認しましょう。
is_Favoriting 関数名は小文字です。
（植西）
--}}

    @if(Auth::user()->is_favoriting($micropost->id))
        {{--お気に入り解除のフォーム --}}
        {!! Form::open(['route' =>['favorites.unfavorite',$micropost->id],'method' => 'delete']) !!}
            {!! Form::submit('Unfavorite',['class' =>"btn btn-danger btn-sm"]) !!}
        {!! Form::close() !!}
    @else
    {{-- お気に入りのフォーム --}}
    {!! Form::open(['route' => ['favorites.favorite',$micropost->id]]) !!}
        {!! Form::submit('Favorite',['class' => "btn btn-primary btn-sm"]) !!}
    {!! Form::close() !!}
    @endif
