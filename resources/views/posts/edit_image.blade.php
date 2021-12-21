@extends('layouts.logged_in')
 
@section('content')
    <h1>{{ $title }}</h1>
    <h2>現在の画像</h2>
    @if($post->image !== '')
        <img src="{{ \Storage::url($post->image) }}">
    @else
        画像はありません。
    @endif
    
    {{Form::open(['url'=>route('posts.update_image', $post), 'files' => true])}}
    {{Form::token()}}
    {{method_field('PATCH')}}
    <div>
        <label>
            画像を選択:
            {{Form::file('image')}}
        </label>
    </div>
    {{Form::submit('更新')}}
    {{Form::close()}}
@endsection