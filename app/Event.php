<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'content',
        'author',
        'team_id',
        'image',
        'public',
        'start',
        'end',
        'place',
        'post_id'
    ];
}
