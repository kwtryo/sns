<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserImageRequest;
use App\Services\FileUploadService;
use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
     //プロフィール詳細画面
    public function show($id){
        $user = User::find($id);
        $posts = Post::recommend($user->id)->orderBy('created_at', 'desc')->limit(10)->get();
        return view('users.show', [
            'title' => 'プロフィール',
            'user' => $user,
            'recommended_posts' => $posts->random(3)->all()
            ]);
    }
    
    //ユーザー情報編集画面
    public function edit(){
        $user = \Auth::user();
        return view('users.edit', [
            'title' => 'プロフィール編集',
            'user' => $user
            ]);
    }
    
    
    //更新処理
    public function update(UserRequest $request){
        $user = \Auth::user();
        $user->update($request->only([
            'name',
            'email',
            'profile'
            ]));
            session()->flash('success', 'プロフィールを編集しました');
        return redirect()->route('users.show', $user->id);
    }
    
    
    //プロフィール画像編集画面
    public function editImage(){
        $user = \Auth::user();
        return view('users.edit_image', [
            'title' => 'プロフィール画像変更画面',
            'user' => $user
            ]);
    }
    
    //画像更新処理
    public function updateImage(UserImageRequest $request, FileUploadService $service){
        $user = \Auth::user();
        $path = $service->saveImage($request->file('image'));
        
        if($user->image !== ''){
            \Storage::disk('public')->delete(\Storage::url($user->image));
        }
        
        $user->update([
            'image' => $path,
            ]);
        session()->flash('success', '画像を変更しました');
        return redirect()->route('users.show', $user->id);
    }
}
