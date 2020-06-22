<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'title',
        'image'.
        'public'
    ];

    protected $appends = ['likes_users_ids'];

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function likes() {
        return $this->hasManyThrough(User::class, Like::class, 'post_id', 'id', 'id', 'user_id');
    }

    public function team() {
        return $this->hasOneThrough(Team::class, TeamPosts::class, 'post_id', 'id', 'id', 'team_id');
    }

    public function getLikesUsersIdsAttribute()
    {
        return $this->likes()->pluck('users.id');
    }
}
