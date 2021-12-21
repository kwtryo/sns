<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Tag;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostImageRequest;
use App\Services\FileUploadService;

class PostController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $follow_user_ids = $user->follow_users->pluck('id');
        $user_posts = $user->posts()->orWhereIn('user_id', $follow_user_ids)->latest()->paginate(5);
        return view('posts.index', [
            'title' => 'タイムライン',
            'posts' => $user_posts,
            'recommended_users' => User::recommend($user->id)->get()
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create', [
            'title' => '新規投稿',
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, FileUploadService $service)
    {
        $path = $service->saveImage($request->file('image'));
        
        $post = Post::create([
            'user_id' => \Auth::user()->id,
            'body' => $request->body,
            'image' => $path,
            ]);
        
        preg_match_all('/#([a-zA-z0-9０-９ぁ-んァ-ヶ亜-熙]+)/u', $request->tags, $match);
        $tags = [];
        foreach($match[1] as $tag){
            $record = Tag::firstOrCreate(['name' => $tag]);
            array_push($tags, $record);
        }
        
        $tags_id = [];
        foreach($tags as $tag){
            array_push($tags_id, $tag['id']);
        }
        $post->tags()->attach($tags_id);
        
        session()->flash('success', 'つぶやきました');
        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       return view('posts.show', [
            'title' => '投稿詳細',
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit', [
            'title' => 'つぶやき編集',
            'post' => $post,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, PostRequest $request)
    {
        $post = Post::find($id);
        $post->update($request->only(['body']));
        session()->flash('success', 'つぶやきを編集しました');
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        
        if($post->image !== ''){
            \Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();
        \Session::flash('success', 'つぶやきを削除しました');
        return redirect()->route('posts.index');
    }
    
    public function editImage($id){
        $post = Post::find($id);
        return view('posts.edit_image', [
            'title' => '画像変更画面',
            'post' => $post,
            ]);
    }
    
    
    public function updateImage($id, PostImageRequest $request, FileUploadService $service){
        $path = $service->saveImage($request->file('image'));
        $post = Post::find($id);
        
        if($post->image !== ''){
            \Storage::disk('public')->delete(\Storage::url($post->image));
        }
        
        $post->update([
            'image' => $path,
            ]);
        session()->flash('success', '画像を変更しました');
        return redirect()->route('posts.index');
    }
    
    
    public function toggleLike($id){
        $user = \Auth::user();
        $post = Post::find($id);
        
        if($post->isLikedBy($user)){
            $post->likes->where('user_id', $user->id)->first()->delete();
            \Session::flash('success', 'いいねを取り消しました');
        }else{
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                ]);
            \Session::flash('success', 'いいねしました');
        }
        return redirect('/posts');
    }
    
}
