<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Follow;
use App\Post;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user=User::findOrFail($request->user()->id);
        $profile=Profile::where('user_id',$request->user()->id)->first();
        $postsCount=Post::where('user_id',$request->user()->id)->count();
        $followers=Follow::where('follower_id',$request->user()->id)->count();
        $following=Follow::where('user_id',$request->user()->id)->count();
        $followDetails=[
            'followers'=>$followers,
            'following'=>$following,
            'posts'=>$postsCount,
            'f_o_n'=>false
        ];
        return response()->json([$user,$profile,$followDetails]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function follow(Request $request){
        $follow = Follow::create([
            'user_id'=>$request->user()->id,
            'follower_id'=>$request->follower_id
        ]);
        if ($follow->save()) {
            # code...
            return response()->json([],201);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        //
        $user=User::findOrFail($id);
        $profile=Profile::where('user_id',$id)->first();
        $posts=Post::where('user_id',$id)->orderBy('created_at','desc')->paginate(4);
        $postsCount=Post::where('user_id',$id)->count();
        $followers=Follow::where('follower_id',$id)->count();
        $following=Follow::where('user_id',$id)->count();
        $f_o_n= !! Follow::where([
            ['user_id','=',$request->user()->id],
            ['follower_id','=',$user->id],
                           ])->count();
        $followDetails=[
            'followers'=>$followers,
            'following'=>$following,
            'f_o_n'=>$f_o_n,
            'posts'=>$postsCount
        ];
        return response()->json([$user,$profile,$posts,$followDetails]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        //dd($id);
        $user=User::findOrFail($id);
        if (Auth::id() == $user->id) {
            return view('users.edit' ,compact('user'));
        }
        else{
            return redirect('post');
        }
        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function updatethumbnail(Request $request)
    {
        $header = $request->header('Authorization');
        if ($header) {

            $token = explode("Bearer ", $header);
            $user = User::where('api_token', $token[1])->first();
            if ($user) {

                if ($request->has('thumbnail')) {

                    $profile=Profile::where('user_id',$user->id)->first();
                    $image = $request->thumbnail;  // your base64 encoded
                    $img=Http::asForm()->post('https://europe-west1-herokuimages.cloudfunctions.net/api/project/image',['base64'=>$image])->json();
                    
                    // $image = str_replace('data:image/jpeg;base64,', '', $image);
                    // $image = str_replace(' ', '+', $image);
                    // $imageName = time().str::random(10).'.'.'png';
                    // $save=File::put(public_path(). '/images/' . $imageName, base64_decode($image));
                    // $img='images/'.$imageName;
                    $profile->thumbnail=$img;
                    if($profile->save()){
                        return response(['profile'=>$profile], 202);
                    }   
                }
            } else {
                return response()->json(['msg' => 'Unverified'], 401);
            }
        } else {
            return response()->json(['msg' => 'token not found'], 404);
        }
    }
    public function update(Request $request, Profile $profile)
    {
        //
            $user=User::where('id','=',$profile->user_id)->first();
            $user->name=$request->name;
            $user->username=$request->username;
            $user->email=$request->email;
            if ($request->previous_password == !Null) {
            if(Hash::check($request->previous_password, $user->password)) {
                if ($request->new_password == !Null) {
                    # code...
                    $user->password=bcrypt($request->new_password);
                    
                }
                else
                {
                    return back();
                }
            }
            else
            {
                return back();
            }    
            }
            if($user->save()){
                return redirect('post');
            }
            else
            {
                return back();
            }
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
