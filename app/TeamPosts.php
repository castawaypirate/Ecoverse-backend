<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamPosts extends Model
{
    public $timestamps = false;

    public function team() {
        return $this->belongsTo(Team::class);
    }
}
