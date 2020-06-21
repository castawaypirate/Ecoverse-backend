<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public function person() {
        $this->belongsTo(User::class);
    }
}
