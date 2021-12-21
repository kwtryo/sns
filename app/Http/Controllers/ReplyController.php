<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reply;
use App\Http\Requests\ReplyRequest;

class ReplyController extends Controller
{
    public function store(ReplyRequest $request){
        Reply::create([
            'post_id' => $request->post_id,
            'user_id' => \Auth::user()->id,
            'body' => $request->body,
            ]);
        session()->flash('success', 'リプライを投稿しました');
        return redirect('/posts');
    }
}
