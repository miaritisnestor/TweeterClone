<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Intervention\Image\ImageManagerStatic as Image;
use App\User;
use App\Post;
use App\Follow;

class UserController extends Controller
{
    public function login(Request $request){

        //Check if the user exists
        $credentials = $request->only('username','password');
        if(Auth::attempt($credentials)){
            return redirect()->route('timeline');
        }else{
            return redirect()->route('home');
        }
    }

    public function register(Request $request){
        
        //New User Registration
        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        //Login the new user
        Auth::login($user);
        
        //redirect to timeline page
        return redirect()->route('timeline');

    }

    public function logout(){
        
        //Logout & redirect to the login/register page
        Auth::logout();
        return redirect()->route('home');

    }

    public function profileInfo($username){

        //Find the user with a specific username
        $user = User::where('username',$username)->get();
        //dd($user);

        //Find the followers & the following users
        $followers = Follow::where('following_id', $user[0]->id)->count();
        $following = Follow::where('follower_id', $user[0]->id)->count();
        
        //Check if you have already follow this user
        $followCheck = Follow::where('following_id', $user[0]->id)->where('follower_id', Auth::id())->count();

        return view('profile',compact('user','followers','following','followCheck'));
    
    }

    public function timeline(){
        
        $following_ids = Follow::where('follower_id',Auth::id())->get();
        
        $user = collect(new User);

        foreach($following_ids as $fid){
            //$users[] = User::where('id',$fid->following_id)->get();
            $users = User::where('id',$fid->following_id)->get();
            $user->push($users[0]);
            
        }

        $post = collect(new Post);

        foreach($user as $usr){
            foreach($usr->posts as $pst){
                //$posts = $usr->posts;
                $post->push($pst);
            }
        }

        $post = $post->sortByDesc('created_at');
        //dd($post[0]->user);

        if($following_ids->count() > 0){
            return view('timeline',compact('post','following_ids'));
        }else{
            return view('timeline',compact('following_ids'));
        }

    }

    public function usersList($page_id){

        //Find all tweeterClone registered Users
        $users = User::all();

        //Save all follows, followings & followChecks to users collection
        foreach($users as $user){
            $followers = Follow::where('following_id', $user->id)->count();
            $user->setAttribute('followers', $followers);

            $following = Follow::where('follower_id', $user->id)->count();
            $user->setAttribute('following', $following);
            //Check if you have already follow this user
            $followCheck = Follow::where('following_id', $user->id)->where('follower_id', Auth::id())->count();
            $user->setAttribute('followCheck', $followCheck);
        }
        //dd($users);
        //Sort users by followers
        $users = $users->sortByDesc('followers');

        return view('list',compact('users','followers','following','followCheck','page_id'));

    }

    public function avatar(Request $request){

        //Validation rules for the post and the image size
        $request->validate([
            'image' => 'required|file|max:1024'
        ]);

        //Upload & Edit the Image
        if($request->hasFile('image')){
            $path = $request->file('image');
            $filename = time().'.'.$path->getClientOriginalExtension();
            Image::make($path)->resize(250, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('avatarImages/'.$filename);
        }

        $id = $request->id;
        $user = User::find($id);
        $user->image = $filename;
        $user->save();

        return redirect()->route('timeline');

    }

}
