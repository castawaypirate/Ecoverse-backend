<?php

namespace App\Http\Controllers;

use App\UserData;
use Illuminate\Http\Request;

class UserDataController extends Controller
{
    public function index()
    {
    }

    public function create(Request $request)
    {
        $userdata = new UserData();

        $userdata->name = $request->input('name');
        $userdata->surname = $request->input('surname');
        $userdata->email = $request->input('email');
        $userdata->user_id = $request->input('user_id');
        $userdata->save();
        return response()->json($request);

    }
}
