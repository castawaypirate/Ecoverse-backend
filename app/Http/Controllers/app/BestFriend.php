<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Friend;

class BestFriend extends Model
{
    public function friend() {
        return $this->belongsTo(Friend::class, 'friend_2', 'id');
    }
}
