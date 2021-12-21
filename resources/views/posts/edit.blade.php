@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
  <h1>{{ $title }}</h1>
    {{Form::open(['url'=>route('posts.update', $post)])}}
    {{Form::token()}}
    {{method_field('PATCH')}}
    <div>
        <label>
            つぶやき:<br>
            {{Form::textarea('body', $post->body)}}
        </label>
    </div>
    {{Form::submit('つぶやきを編集')}}
    {{Form::close()}}
@endsection