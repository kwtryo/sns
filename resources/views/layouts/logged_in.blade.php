@extends('layouts.default')
 
@section('header')
<header>
    <ul class="header_nav">
        <li>
          <a href="{{ route('posts.index') }}">
            タイムライン
          </a>
        </li>
        <li>
          <a href="{{ route('likes.index') }}">
            いいねリスト
          </a>
        </li>
        <li>
          <a href="{{route('users.show', \Auth::user()->id)}}">
            ユーザープロフィール
          </a>
        </li>
        <li>
          <a href="{{route('follows.index')}}">
            フォロー一覧
          </a>
        </li>
        <li>
          <a href="{{url('\follower')}}">
            フォロワー
          </a>
        </li>
        <li>
            {{Form::open(['url'=>route('logout')])}}
            {{Form::token()}}
            {{Form::submit('ログアウト')}}
            {{Form::close()}}
        </li>
    </ul>
    <p>{{Auth::user()->name}}さん、こんにちは！</p>
</header>
@endsection