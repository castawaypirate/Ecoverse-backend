<?php

namespace App\Http\Controllers;

use App\UserData;
use Illuminate\Http\Request;

class UserDataController extends Controller
{
    public function index()
    {
        $all = UserData::find(2);
        echo $all->something();
        // $cars=array("Volvo","BMW","Toyota");
        // return view("Demos")->with('all',$all);

        // return view('demos');
    }

    public function create(Request $request)
    {
        $userdata = new UserData();

        // $table->increments('id');
        // $table->integer('user_id')->unsigned();
        // $table->foreign('user_id')->references('id')->on('users');
        // $table->string('name');
        // $table->string('surname');
        // $table->string('email')->unique();
        // $table->timestamp('email_verified_at')->nullable();
        // $table->string('image')->default('image.png');
        // $table->date('birthday');
        // $table->string('location',50)->nullable();
        // $table->timestamps();
        $userdata->name = $request->input('name');
        $userdata->surname = $request->input('surname');
        $userdata->email = $request->input('email');
        $userdata->user_id = $request->input('user_id');
        $userdata->save();
        return response()->json($request);

    }
}
