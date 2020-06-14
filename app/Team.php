<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Team
 *
 * @property int         $id
 * @property string      $name
 * @property string      $description
 * @property boolean     $public
 * @property string      $created_at
 * @property string      $updated_at
 */
class Team extends Model
{
    public function members() {
        return $this->belongsToMany(User::class, "team_members", "team_id", "user_id");
    }

    public function posts() {
        return $this->hasManyThrough(Post::class, TeamPosts::class, "team_id", "id", "id", "post_id");
    }
}
