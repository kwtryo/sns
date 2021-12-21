@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
  <h1>{{ $title }}</h1>
  
  <ul class="follow_users">
      @forelse($follow_users as $follow_user)
      <li class="user_list">
            
            <a href="{{route('users.show', $follow_user->id)}}">
              @if($follow_user->image !== '')
              <img src="{{ asset('storage/' . $follow_user->image )}}">
              @else
              <img src="{{asset('storage/images/no_image.png')}}">
              @endif
              {{$follow_user->name}}
            </a>
            {{Form::open(['url'=>route('follows.destroy', $follow_user)])}}
            {{Form::token()}}
            {{method_field('DELETE')}}
            {{Form::submit('フォロー解除')}}
            {{Form::close()}}
      </li>
      @empty
      <li>フォローしているユーザーはいません</li>
      @endforelse
  </ul>
@endsection