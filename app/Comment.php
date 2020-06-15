<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function author() {
        return $this->belongsTo(User::class, 'id', 'author_id');
    }

    public function firstLevelComments() {
        return $this->hasMany(Comment::class);
    }

    public function comments() {
        return $this->firstLevelComments()->with('comments');
    }
}
