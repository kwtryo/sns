@extends('layouts.logged_in')

@section('title', $title)
 
@section('content')
    <h1>{{ $title }}</h1>
    
    <div>
        現在の画像<br>
        @if($user->image !== '')
            <img src="{{asset('storage/' . $user->image)}}">
        @else
            <img src="{{asset('storage/images/no_image.png')}}">
        @endif
    </div>
    
    {{Form::open(['url'=>route('users.update_image'), 'files' => true])}}
        {{Form::token()}}
        {{method_field('PATCH')}}
        {{Form::file('image')}}
        {{Form::submit('更新')}}
    {{Form::close()}}
@endsection