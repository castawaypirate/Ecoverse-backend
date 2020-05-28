<?php

namespace App\Http\Controllers;

use App\Team_Member;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
      //   $request->validate([
      //      'role'=>'required' ]);

        $team_Member = new Team_Member();
        $team_Member->save();
        return response('You have successfully joined the team')->json(
            $team_Member->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $team_Member = Team_Member::find($id);
        if($team_Member->role = 'admin' || $team_Member->role = 'administrator')
        { 
            $team_Member->role = $request->input('role');
            return response(['message'=>'You have successfully updated your role!']);
        }
        return response(['message'=>'You must be an admin to perform this action']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team_Member = Team_Member::find($id);
        $team_Member->delete();
        return response(['message'=>'You have been removed from the team']);
    }
}
