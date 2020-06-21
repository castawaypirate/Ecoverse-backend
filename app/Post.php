<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    protected $fillable = [
        'content',
        'author_id',
        'title',
        'team_id',
        'image'.
        'public'
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likes() {
        $this->hasMany(Like::class);
    }
}
