<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public function person() {
        return $this->belongsTo(User::class);
    }
}
