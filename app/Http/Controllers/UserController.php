<?php

namespace App\Http\Controllers;

use App\User;
use App\UserData;
use Illuminate\Http\Request;
use DB;
use Auth;
use Hash;
use Symfony\Component\Config\Definition\Exception\Exception;

class UserController extends Controller
{

    public function index()
    {
        // $all = User::find(1);
        // echo $all->getData()->get()[0]->user_id;
        echo 'hello'=='hello';
        // $all = User::all();
        // echo $all;
        // $cars=array("Volvo","BMW","Toyota");
        // return view("Demos")->with('all',$all);

        // return view('demos');
    }

    public function create(Request $request)
    {
        $user = new User();
        $userdata = new UserData();
        DB::beginTransaction();
        try
        {
            $request->validate(([
                'username' => 'required|max:55',
                'email' => 'email|required',
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

            if($user && $userdata)
            {
                DB::commit();
                return response(['user'=>$user,'accessToken'=>$accessToken]);
                // return response()->json($request);
            }
            else
            {
                DB::rollback();
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            return redirect()->back();
        }
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
        return response(['user'=>auth()->user(),'accessToken'=>$accessToken]);
    }

    public function update(Request $request)
    {
        $request->validate(([
            'password' => 'required'
        ]));

        $user = Auth::user();
        $userdata = UserData::where('user_id','=',$user->id)->firstOrFail();

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
        // $user = User::find($id);
        // $user->username = $request->input('username');
        // $user->password = $request->input('password');
        // $user->role = $request->input('role');
        // $user->save();
        // return response()->json($request);

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

    public function get($id)
    {
        $user = User::find($id);
        $userdata = UserData::where('user_id','=',$id)->firstOrFail();
        return response()->json([$user,$userdata]);
    }
}
