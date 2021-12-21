@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
  <h1>{{ $title }}</h1>
  
    {{Form::open(['url'=>route('posts.store'), 'files' => true])}}
    {{Form::token()}}
    <div>
        <label>
            つぶやき:<br>
            {{Form::textarea('body', null)}}
        </label>
    </div>
    <div>
      <label>
        画像:
        {{Form::file('image')}}
      </label>
    </div>
    <div>
      <label>
        タグ:
        {{Form::text('tags')}}
      </label>
    </div>
    {{Form::submit('つぶやく')}}
    {{Form::close()}}

@endsection