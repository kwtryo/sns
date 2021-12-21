<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //いいね一覧
    public function index(){
        $like_posts = \Auth::user()->likePosts()->latest('likes.created_at')->paginate(5);
        return view('likes.index', [
            'title' => 'いいね一覧',
            'like_posts' => $like_posts,
            ]);
    }
}
