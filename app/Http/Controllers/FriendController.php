<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Friend;

class FriendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllFriends() {
        $friends = Friend::with('getBestFriends.friend')->get()->toJson();

        return view('home')->with([
            'friends' => $friends
        ]);
    }
}
