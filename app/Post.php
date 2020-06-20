<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'image'.
        'public'
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likes() {
        $this->hasMany(Like::class);
    }

    public function team() {
        return $this->hasOneThrough(Team::class, TeamPosts::class, 'post_id', 'id', 'id', 'team_id');
    }
}
