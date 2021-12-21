@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
  <h1>{{ $title }}</h1>
  
  <ul class="posts">
      @forelse($like_posts as $like_post)
      <li class="post">
        <div class="post_content">
          <div class="post_body">
            <div class="post_body_heading">
              <a href="{{route('users.show', $like_post->user->id)}}">
                @if($like_post->user->image !== '')
                <img src="{{asset('storage/' . $like_post->user->image)}}">
                @else
                <img src="{{asset('storage/images/no_image.png')}}">
                @endif
              {{$like_post->user->name}}
              </a>
              : ({{$like_post->created_at}})
            </div>
            <div class="post_body_main">
              <div class="post_body_main_body">
                {{$like_post->body}} 
              </div>
              <div class="post_body_main_img">
                @if($like_post->image !== '')
                  <img src="{{asset('storage/' . $like_post->image)}}">
                @endif
              </div>
              <div class="post_body_main_tags">
                <ul>
                  @if($like_post->tags !== '')
                  @foreach($like_post->tags as $tag)
                    <li>#{{$tag->name}}</li>
                  @endforeach
                @endif
                </ul>
              </div>
            </div>
            <div class="post_body_footer">
              <a class="like_button">{{ $like_post->isLikedBy(Auth::user()) ? '♥' : '♡' }}</a>
              {{Form::open(['url'=>route('posts.toggle_like', $like_post->id), 'class' => 'like'])}}
              {{Form::token()}}
              {{method_field('PATCH')}}
              {{Form::close()}}
            </div>
          </div>
          
          <div class="post_replies">
            <ul>
              @forelse($like_post->replies as $reply)
              <li>
                <div class="post_replies_body_heading">
                  {{$reply->user->name}}:
                </div>
                <div class="post_replies_body_main">
                  <div class="post_replies_body_main_body">
                    {{$reply->body}}
                  </div>
                  <div class="post_replies_body_main_img">
                    
                  </div>
                </div>
                <div class="post_replies_body_footer">
                  
                </div>
              </li>
              @empty
              <li>リプライはありません。</li>
              @endforelse
            </ul>
              {{Form::open(['url'=>route('replies.store')])}}
              {{Form::token()}}
              {{Form::hidden('post_id',$like_post->id)}}
              <label>
                リプライを追加:
                {{Form::text('body', null)}}
              </label>
              {{Form::submit('リプライを送信')}}
              {{Form::close()}}
              </li>
            @empty
            <li>つぶやきはありません。</li>
            @endforelse
  </ul>
  {{$like_posts->links()}}
<script>
  /* global $ */
  $('.like_button').on('click', (event) => {
      $(event.currentTarget).next().submit();
  })
</script>
@endsection