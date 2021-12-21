@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
  <h1>{{ $title }}</h1>
  
  <ul class="follower">
      @forelse($followers as $follower)
      <li class="user_list">
            <a href="{{route('users.show', $follower->id)}}">
              @if($follower->image !== '')
              <img src="{{asset('storage/' . $follower->image)}}">
              @else
              <img src="{{asset('storage/images/no_image.png')}}">
              @endif
              {{$follower->name}}
            </a>
            @if(Auth::user()->isFollowing($follower))
            {{Form::open(['url'=>route('follows.destroy', $follower), 'class' => 'follow'])}}
            {{Form::token()}}
            {{method_field('DELETE')}}
            {{Form::submit('フォロー解除')}}
            {{Form::close()}}
            @else
            {{Form::open(['url'=>route('follows.store'), 'class' => 'follow'])}}
            {{Form::token()}}
            {{Form::hidden('follow_id', $follower->id)}}
            {{Form::submit('フォロー')}}
            {{Form::close()}}
            @endif
      </li>
      @empty
      <li>フォロワーはいません</li>
      @endforelse
  </ul>
@endsection