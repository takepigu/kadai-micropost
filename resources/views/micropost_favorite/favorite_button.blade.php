@if (Auth::id() != $user->id)
    @if(Auth::user()->is_Favoriting($micropost->id))
        {{--お気に入り解除のフォーム --}}
        {!! Form::open(['route' =>['micropost.unfavorite',$micropost->id],'method' => 'delete']) !!}
            {!! Form::submit('Unfavorite',['class' =>"btn btn-danger btn-block"]) !!}
        {!! Form::close() !!}
    @else
    {{-- お気に入りのフォーム --}}
    {!! Form::open(['route' => ['micropost.favorite',$micropost->id]]) !!}
        {!! Form::submit('Favorite',['class' => "btn btn-primary btn-block"]) !!}
    {!! Form::close() !!}
    @endif
@endif