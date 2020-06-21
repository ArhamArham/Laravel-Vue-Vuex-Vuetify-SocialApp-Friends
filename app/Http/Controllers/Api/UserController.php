<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use App\Profile;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function signup(Request $request)
    {
        $user=new User([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
            ]);
        
        $credentials=[
            'email'=>$request->email,
            'password'=>$request->password
        ];
        if($user->save()){
            $profile=new Profile([
                'user_id'=>$user->id
            ]);
            $profile->save();
            if(Auth::attempt($credentials)){
                $token=Str::random(80);
                Auth::user()->api_token=$token;
                Auth::user()->save();
                return response()->json(['token'=>$token],200);
            }
        }
    }
    public function login(Request $request)
    {
        $credentials=$request->only('email','password');
        if(Auth::attempt($credentials)){
            $token=Str::random(80);
            Auth::user()->api_token=$token;
            Auth::user()->save();
            return response()->json(['token'=>$token],200);
        }
        return response()->json(['status'=>'Email or password is wrong'],403);
    }
    public function loggedUser(Request $request)
    {
        $header = $request->header('Authorization');
        if ($header) {

            $token = explode("Bearer ", $header);
            $user = User::where('api_token', $token[1])->first();
            if ($user) {
                $newuser=User::where('id',$user->id)->orderBy('created_at','desc')->first();
                $profile=Profile::where('user_id',$user->id)->first();
                // $posts=Post::where('user_id',$user->id)->orderBy('created_at','desc')->get();
                return response()->json([$newuser,$profile]);
            } else {
                return response()->json(['msg' => 'Unverified'], 401);
            }
        } else {
            return response()->json(['msg' => 'token not found'], 404);
        }
    }
    public function loggedUserPosts(Request $request){
        $header = $request->header('Authorization');
        if ($header) {

            $token = explode("Bearer ", $header);
            $user = User::where('api_token', $token[1])->first();
            if ($user) {
                $posts=Post::where('user_id',$user->id)->orderBy('created_at','desc')->paginate(4);
                return response()->json(['posts'=>$posts]);
            } else {
                return response()->json(['msg' => 'Unverified'], 401);
            }
        } else {
            return response()->json(['msg' => 'token not found'], 404);
        }
    }
    public function verify(Request $request){
        $user=User::with('profile')->where('id',$request->user()->id)->first();
        return $user;
    }
    public function verifyEmail(Request $request)
    {
        if($request->id){
            $request->validate([
                'email' => 'required|unique:users,email,'.$request->id,
            ]);
        }else{
            $request->validate([
                'email' => 'required|unique:users'
            ]);
        }
        return response()->json(['message'=>'Email availabe'], 200);
    }
    public function updatesettings(Request $request, $id)
    {   
        $user=User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        if ($request->password ==! Null) {
            if(Hash::check($request->password, $user->password)) {
                if ($request->new_password == !Null) {
                    # code...
                    $user->password=bcrypt($request->new_password);
                    
                }
            }
            else
            {
                return response()->json(['message','Password doesnot match!!!'], 403);
            }    
        }
        $user->save();
        return response()->json(['user'=>$user], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        if($user){
            $user->name=$request->name;
            $user->bio= $request->bio;
            $user->save();
            return $user;
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
