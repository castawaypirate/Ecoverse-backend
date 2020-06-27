<?php

namespace App\Http\Controllers;

use App\User;
use App\UserData;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Validator;


class UserController extends Controller
{

    public function authenticated()
    {
        $user = Auth::user();
        $user = User::with('data')->find($user->id);
        return response()->json($user);
    }

    public function create(Request $request)
    {
        Validator::extend('valid_username', function($attr, $value){

            return preg_match('/^\S*$/u', $value);

        });
        $user = new User();
        $userdata = new UserData();
        $request->validate(([
            'username' => 'required|max:55|valid_username|min:4|unique:users,username',
            'email' => 'required|email|max:255|unique:users_data,email',
            'password' => 'required|confirmed',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]));

        $user->username = $request->input('username');

        $password = Hash::make($request->password);
        $user->password = $password;

        $user->role = $request->input('role');

        $user->save();

        $userdata->email = $request->input('email');
        $userdata->user_id = $user->id;
        if ($request->has('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $userdata->image = asset('/images/' .$name);
        }
        $userdata->save();

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user'=>$user,'accessToken'=>$accessToken]);

    }

    public function login(Request $request)
    {
        $logindata = $request->validate(([
            'username' => 'required',
            'password' => 'required'
        ]));

        if(!auth()->attempt(($logindata)))
        {
            throw new \Exception('Invalid credentials');
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $user = Auth::user();
        $user = User::with('data')->find($user->id);
        return response(['user'=> $user,'accessToken'=>$accessToken]);
    }

    public function update(Request $request)
    {
        $request->validate(([
            'password' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]));

        $user = Auth::user();
        $userdata = UserData::where('user_id','=',$user->id)->firstOrFail();

        if(Hash::check($request->password, $user->password))
        {
            if ($request->input('name') !== null ) {
                $userdata->name = $request->input('name');
            }
            if ($request->input('surname') !== null ) {
                $userdata->surname = $request->input('surname');
            }
            if ($request->input('email') !== null ) {
                $userdata->email = $request->input('email');
            }
            if ($request->input('image') !== null ) {
                $userdata->image = $request->input('image');
            }
            if ($request->input('birth_date') !== null ) {
                $userdata->birth_date = $request->input('birth_date');
            }
            if ($request->input('location') !== null ) {
                $userdata->location = $request->input('location');
            }
            if ($request->has('image')) {
                $image = $request->file('image');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
                $userdata->image = asset('/images/' .$name);
            }
            $userdata->save();
            if ($request->input('username') !== null ) {
                $user->username = $request->input('username');
            }
            if ($request->input('new_password') !== null ) {
                $user->password = Hash::make($request->password);
            }
            if ($request->input('role') !== null ) {
                $user->role = $request->input('role');
            }
            $user->save();
            return response([$user,$userdata]);
        }
        throw new \Exception('Wrong password');
    }

    public function updateImg(Request $request) {
        $request->validate(([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]));

        $user = Auth::user();
        $userdata = UserData::where('user_id','=',$user->id)->firstOrFail();

        if ($request->has('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $userdata->image = asset('/images/' .$name);
        }
        $userdata->save();

        return response()->json(['image' => $userdata->image]);
    }

    public function delete(Request $request,$id)
    {
        $request->validate(([
            'password' => 'required'
        ]));

        $user = User::find($id);
        $userdata = UserData::where('user_id','=',$id)->firstOrFail();

        if(Hash::check($request->password, $user->password))
        {
            $userdata->delete();
            $user->delete();
            return response(['message'=>'User is deleted']);
        }
        return response((['message'=>'Wrong password']));
    }

    public function getUser($id)
    {
        $user = User::find($id);
        $userdata = UserData::where('user_id','=',$id)->firstOrFail();
        return response()->json($user);
    }

    public function getUserData($id)
    {
        $user = User::find($id);
        $userdata = UserData::where('user_id','=',$id)->firstOrFail();
        return response()->json($userdata);
    }
}
