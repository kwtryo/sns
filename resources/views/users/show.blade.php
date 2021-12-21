@extends('layouts.logged_in')

@section('title', $title)
 
@section('content')
    <h1>{{ $title }}</h1>
    <div class="user_profile">
      @if($user->image !== '')
      <img src="{{asset('storage/' . $user->image)}}">
      @else
      <img src="{{asset('storage/images/no_image.png')}}">
      @endif
      {{$user->name}}
    </div>
    <div>
        自己紹介<br>
        @if($user->profile !== '')
        {{$user->profile}}
        @else
        <p>自己紹介文が設定されていません。</p>
        @endif
    </div>
    @if($user->id === \Auth::user()->id)
        [<a href="{{route('users.edit')}}">プロフィールを編集</a>]<br>
        [<a href="{{route('users.edit_image')}}">プロフィール画像を変更</a>]
    @else
        @if(Auth::user()->isFollowing($user))
          {{Form::open(['url'=>route('follows.destroy', $user), 'class' => 'follow'])}}
          {{Form::token()}}
          {{method_field('DELETE')}}
          {{Form::submit('フォロー解除')}}
          {{Form::close()}}
        @else
          {{Form::open(['url'=>route('follows.store', $user), 'class' => 'follow'])}}
          {{Form::token()}}
          {{Form::hidden('follow_id', $user->id)}}
          {{Form::submit('フォロー')}}
          {{Form::close()}}
        @endif
        @forelse($recommended_posts as $recommended_post)
        <h3>{{$user->name}} さんのおすすめのつぶやき</h3>
            <li class="post">
                <div class="post_content">
              <div class="post_body">
                <div class="post_body_heading">
                  {{$recommended_post->user->name}}:
                  ({{$recommended_post->created_at}})
                </div>
                <div class="post_body_main">
                  <div class="post_body_main_body">
                    {{$recommended_post->body}} 
                  </div>
                  <div class="post_body_main_img">
                    @if($recommended_post->image !== '')
                      <img src="{{asset('storage/' . $recommended_post->image)}}">
                    @endif
                  </div>
                </div>
              </div>
              
              <div class="post_replies">
                <ul>
                  @forelse($recommended_post->replies as $reply)
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
                  {{Form::hidden('post_id',$recommended_post->id)}}
                  <label>
                    リプライを追加:
                    {{Form::text('body', null)}}
                  </label>
                  {{Form::submit('リプライを送信')}}
                  {{Form::close()}}
            </li>
        @empty
            
        @endforelse
    @endif
    
@endsection