<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    protected $fillable = [
        'content',
        'author',
        'team_id',
        'image'.
        'public'
    ];

}
