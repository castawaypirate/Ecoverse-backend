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
        })->without('members');

        return response()->json($teams->get());
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function readOne($team_id) {
        $team = Team::where('id', $team_id);

        if ($team->first()->public) {
            $team = $team->with(['posts']);
        } else {
            $user = Auth::user();
            $is_member = $team->whereHas('members', function (Builder $q) use ($user) {
                $q->where('users.id', $user->id);
            })->first();
            if ($is_member) {
                $team = $team->with(['posts']);
            }
        }

        return response()->json($team->first());
    }

    public function readForEdit($team_id) {
        $team = Team::where('id', $team_id);

        $teams = $team->with(['posts', 'members.data', 'pendingMembers.data']);

        return response()->json($teams->first());
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
            'public' => ['boolean']
        ]);

        $team = Team::find($team_id);

        if (isset($request->name) && $request->name) {
            $team->name = $request->name;
        }

        if (isset($request->description) && $request->description) {
            $team->description = $request->description;
        }

        if (isset($request->public)) {
            $team->public = $request->public;
        }

        $team->save();

        return response()->json(
            $team
        );
    }

    public function editMany() {
        $user = Auth::user();
        $teams = Team::whereHas('members', function (Builder $q) use ($user) {
            $q->where('users.id', $user->id)->where('role_id', config('teamMemberRoles.adminRole'))->where('team_members.status', 'like', 'accepted');
        });

        return response()->json($teams->get());
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

    public function getMembers($team_id) {
        $team = new Team($team_id);
        $members = $team->load('members')->where('team_members.status', 'like', 'accepted')->get();

        return response()->json($members);
    }

    private function createMember($team_id, $user_id) {
        $member = new TeamMember();
        $member->user_id = $user_id;
        $member->team_id = $team_id;
        $member->role_id = config('teamMemberRoles.simpleMember');
        $member->save();
    }

    public function teamMemberApply($team_id) {
        $user = Auth::user();
        $member = TeamMember::where('team_id', $team_id)->where('user_id', $user->id)->first();
        if ($member) {
            throw new \Exception('You are already member or you have already applied for this team');
        }

        $this->createMember($team_id, $user->id);

        return response()->json([
            'message' => 'Successful application of user for team with id: '.$team_id
        ]);
    }

    public function teamMemberAccept(Request $request, $team_id, $user_id) {
        $member = TeamMember::where('user_id', $user_id)->where('team_id', $team_id)->where('status', 'like', 'pending')->first();
        if (!$member) {
            throw new \Exception('User ddin\'t apply for this team or team doesn\'t exist');
        }

        $member->status = 'accepted';
        $member->save();
        return response()->json([
            'message' => 'Successful addition of member'
        ]);
    }

    // public function addMembers(Request $request, $team_id) {
    //     $request->validate([
    //         'user_ids' => ['required']
    //     ]);

    //     foreach ($request->user_ids as $user_id) {
    //         $user = User::find($user_id);
    //         $this->createMember($user->id, $team_id);
    //     }

    //     return response()->json([
    //         'message' => 'Successful addition of members'
    //     ]);
    // }

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

    public function deleteMember($team_id, $user_id) {
        TeamMember::where('team_id', $team_id)->where('user_id', $user_id)->delete();

        return response()->json([
            'message' => 'Successful deletion of member'
        ]);
    }
}
