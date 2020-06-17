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

    public function index()
    {
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
            'password' => 'required|confirmed'
        ]));

        $user->username = $request->input('username');

        $password = Hash::make($request->password);
        $user->password = $password;

        $user->role = $request->input('role');
        $user->save();

        $userdata->email = $request->input('email');
        $userdata->user_id = $user->id;
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
            return response((['message'=>'Invalid credentilas']));
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user'=>auth()->user(),'accessToken'=>$accessToken]);
    }

    public function update(Request $request,$id)
    {
        Validator::extend('valid_username', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        $user = User::find($id);
        $userdata = UserData::where('user_id','=',$id)->firstOrFail();

        $request->validate(([
            'password' => 'required',
            'username' => 'max:55|valid_username|min:4|unique:users,username,'. $user->id,
        ]));

        if(Hash::check($request->password, $user->password))
        {
            $userdata->name = $request->input('name');
            $userdata->surname = $request->input('surname');
            $userdata->email = $request->input('email');
            $userdata->image = $request->input('image');
            $userdata->birthday = $request->input('birthday');
            $userdata->location = $request->input('location');
            $userdata->save();
            $user->username = $request->input('username');
            $user->password = Hash::make($request->password);;
            $user->role = $request->input('role');
            $user->save();
            return response([$user,$userdata]);
        }
        return response(['message'=>'Wrong password']);
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
