<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function data()
    {
        return $this->hasOne(UserData::class);
    }

    public function teams() {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id');
    }

    public function team($team_id) {
        return $this->teams()->wherePivot('team_id', '=', $team_id);
    }

    public function teamRoles() {
        return $this->belongsToMany(MemberRole::class, 'team_members', 'user_id', 'role_id');
    }

    public function teamRole($team_id) {
        return $this->teamRoles()->wherePivot('team_id', '=', $team_id)->first();
    }
}
