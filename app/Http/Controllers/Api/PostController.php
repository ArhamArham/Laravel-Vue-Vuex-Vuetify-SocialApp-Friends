<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Comment;
use App\Post;
use App\Reply;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {   
        $ids = [];
        foreach ($request->user()->follows as $id) {
            $ids[] = $id->id;
        }
        $posts = Post::with('user','comments', 'comments.user', 'comments.replies', 'comments.replies.user')->whereIn('user_id', $ids)->orderBy('created_at', 'desc')->paginate(4);
        return response()->json(['posts' => $posts]);
    }
    public function post()
    {
        //dd(Auth::user()->posts);
        $ids = [];
        foreach (Auth::user()->follows as $id) {
            $ids[] = $id->id;
        }

        $posts = Post::with('user', 'user.profile', 'comments', 'comments.user', 'comments.replies', 'comments.replies.user')->whereIn('user_id', $ids)->orderBy('created_at', 'desc')->paginate(3);
        return $posts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $header = $request->header('Authorization');
        if ($header) {

            $token = explode("Bearer ", $header);
            $user = User::where('api_token', $token[1])->first();
            if ($user) {

                if ($request->has('thumbnail')) {
                    $image = $request->thumbnail;  // your base64 encoded
                    $img=Http::asForm()->post('https://europe-west1-herokuimages.cloudfunctions.net/api/project/image',['base64'=>$image])->json();
                    // $image = str_replace('data:image/jpeg;base64,', '', $image);
                    // $image = str_replace(' ', '+', $image);
                    // $imageName = time() . str::random(10) . '.' . 'png';
                    // $save = File::put(public_path() . '/images/' . $imageName, base64_decode($image));
                    // $img = 'images/' . $imageName;
                    $post = Post::create([
                        'user_id' => $user->id,
                        'thumbnail' => $img,
                        'description' => $request->description
                    ]);
                }   
                $newPost=Post::with('user','comments', 'comments.user', 'comments.replies', 'comments.replies.user')->where('id',$post->id)->first();
                return response(['post'=>$newPost], 202);
            } else {
                return response()->json(['msg' => 'Unverified'], 401);
            }
        } else {
            return response()->json(['msg' => 'token not found'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }
    public function profilePosts(Request $request){
        $posts = Post::where('user_id',$request->user()->id)->with('user')->orderBy('created_at', 'desc')->paginate(4);
        return response()->json(['posts' => $posts]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
        return view('posts.create', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $header = $request->header('Authorization');
        if ($header) {

            $token = explode("Bearer ", $header);
            $user = User::where('api_token', $token[1])->first();
            if ($user) {

                if ($request->has('thumbnail')) {

                    $image = $request->thumbnail;  // your base64 encoded
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = time() . str::random(10) . '.' . 'png';
                    $save = File::put(public_path() . '/images/' . $imageName, base64_decode($image));
                    $img = 'images/' . $imageName;
                    $post->thumbnail=$img;
                    $post->description=$request->description;
                    if($post->save()){
                        $newPost=Post::with('user','comments', 'comments.user', 'comments.replies', 'comments.replies.user')->where('id',$post->id)->first();
                        return response(['post'=>$newPost], 202);
                    }   
                }
            } else {
                return response()->json(['msg' => 'Unverified'], 401);
            }
        } else {
            return response()->json(['msg' => 'token not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
        //dd($post);
        $post->delete();
        return response(null, 202);
    }
    public function comment(Request $request)
    {
        $header = $request->header('Authorization');
        if ($header) {

            $token = explode("Bearer ", $header);
            $user = User::where('api_token', $token[1])->first();
            if ($user) {

                $c = Comment::create([
                    'post_id' => $request->postId,
                    'user_id' => $user->id,
                    'comment' => $request->comment
                ]);
                $comment = Comment::with('user', 'replies')->where('id', $c->id)->first();
                return $comment;
            } else {
                return response()->json(['msg' => 'Unverified'], 401);
            }
        } else {
            return response()->json(['msg' => 'token not found'], 404);
        }
    }
    public function reply(Request $request)
    {
        $header = $request->header('Authorization');
        if ($header) {

            $token = explode("Bearer ", $header);
            $user = User::where('api_token', $token[1])->first();
            if ($user) {

                $r = Reply::create([
                    'post_id' => $request->postId,
                    'user_id' => $user->id,
                    'comment_id' => $request->commentId,
                    'reply' => $request->reply
                ]);
                $reply = Reply::with('user')->where('id', $r->id)->first();
                return $reply;
            } else {
                return response()->json(['msg' => 'Unverified'], 401);
            }
        } else {
            return response()->json(['msg' => 'token not found'], 404);
        }
    }
}
