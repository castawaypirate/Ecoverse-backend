<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BestFriend;

class Friend extends Model
{
    public function getBestFriends() {
        return $this->hasMany(BestFriend::class, 'friend_1', 'id');
    }
}
