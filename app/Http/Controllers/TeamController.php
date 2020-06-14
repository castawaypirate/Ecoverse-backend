<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\TeamMember;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function read() {
        $user = Auth::user();
        $teams = Team::whereHas('members', function (Builder $q) use ($user) {
            $q->where('users.id', $user->id);
        });

        return response()->json($teams->get());
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function readOne($team_id) {
        $team = Team::where('id', $team_id);

        if ($team->public) {
            $team = $team->with('posts');
        } else {
            $user = Auth::user();
            $is_member = $team->whereHas('members', function (Builder $q) use ($user) {
                $q->where('users.id', $user->id);
            });
            if ($is_member) {
                $team = $team->with('posts');
            }
        }

        return response()->json($team->get());
    }

    /**
     * Store a newly created team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response The team that had been created
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required'],
            'description'   => ['required'],
            'public'   => ['required'],
        ]);

        $team = new Team();
        $team->name = $request->name;
        $team->description = $request->description;
        $team->public = $request->public;
        $team->save();

        $user = Auth::user();
        $teamMember = new TeamMember();
        $teamMember->team_id = $team->id;
        $teamMember->user_id = $user->id;
        $teamMember->role_id = config('teamMemberRoles.adminRole');
        $teamMember->save();

        return response()->json($team);
    }

    /**
     * Update the specified team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $team_id)
    {
        $request->validate([
            'publish' => ['boolean']
        ]);

        $team = Team::find($team_id);

        if (isset($request->name) && $request->name) {
            $team->name = $request->name;
        }

        if (isset($request->description) && $request->description) {
            $team->description = $request->description;
        }

        if (isset($request->publish)) {
            $team->publish = $request->publish;
        }

        return response()->json(
            $team
        );
    }

    /**
     * Remove the specified team from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($team_id)
    {
        Team::destroy($team_id);

        return response()->json([
            'message' => 'Successful deletion of team'
        ]);
    }

    public function createMember(Request $request, $team_id) {
        $request->validate([
            'user_id' => ['required']
        ]);

        $user = User::find($request->user_id);

        $member = new TeamMember();
        $member->user_id = $user->id;
        $member->team_id = $team_id;
        $member->role_id = config('teamMemberRoles.simpleMember');
        $member->save();
    }

    public function editMember(Request $request, $team_id, $member_id) {
        $request->validate([
            'role_id' => 'required'
        ]);

        $member = TeamMember::find($member_id)->where('team_id', $team_id);

        if (!$member) {
            throw new \Exception('Member is not part of this team');
        }

        $member->role_id = $request->role_id;
        $member->save();

        return response()->json([
            'message' => 'Successful editing of member'
        ]);
    }

    public function deleteMember($member_id) {
        TeamMember::destroy($member_id);

        return response()->json([
            'message' => 'Successful deletion of member'
        ]);
    }
}
