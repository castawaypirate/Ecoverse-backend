<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class TeamMemberActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$neededActions)
    {
        $user = Auth::user();
        $role = $user->teamRole($request->team_id);
        $actions = [];
        if ($role->create) {
            array_push($actions, 'create');
        }
        if ($role->edit) {
            array_push($actions, 'edit');
        }
        if ($role->delete) {
            array_push($actions, 'delete');
        }

        $lessActions = array_diff($neededActions, $actions);

        if (!empty($lessActions)) {
            throw new \Exception("You dont have the permission to: ". implode(', ',$lessActions));
        }

        return $next($request);
    }
}
