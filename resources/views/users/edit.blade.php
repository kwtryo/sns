@extends('layouts.logged_in')

@section('title', $title)
 
@section('content')
    <h1>{{ $title }}</h1>
    
    [<a href="{{route('users.show', \Auth::user()->id)}}">戻る</a>]
    
    {{Form::open(['url'=>route('users.update')])}}
    {{Form::token()}}
    {{method_field('PATCH')}}
    <div>
        <label>
            名前:
            {{Form::text('name', $user->name)}}
        </label>
    </div>
    <div>
        <label>
            メールアドレス:
            {{Form::email('email', $user->email)}}
        </label>
    </div>
    <div>
        <label>
            自己紹介:<br>
            {{Form::textarea('profile', $user->profile)}}
        </label>
    </div>
    {{Form::submit('更新')}}
    {{Form::close()}}
@endsection