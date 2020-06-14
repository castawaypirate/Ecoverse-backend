<?php

namespace App\Http\Controllers;

use App\TeamMember;
use Auth;

class TeamMemberController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($member_id)
    {
        $user = Auth::user();
        $member = TeamMember::find($member_id)->where('user_id', $user->id);

        if (!$member) {
            throw new \Exception('You are not this member');
        }

        $member->delete();

        return response()->json([
            'message'=>'You left the team'
        ]);
    }
}
