<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function author() {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function firstLevelComments() {
        return $this->hasMany(Comment::class);
    }

    public function comments() {
        return $this->firstLevelComments()->with('comments');
    }

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function likes() {
        $this->hasMany(Like::class);
    }
}
