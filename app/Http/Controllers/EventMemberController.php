<?php

namespace App\Http\Controllers;

use App\EventMember;
use Illuminate\Http\Request;

class EventMemberController extends Controller
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
        $request->validate([
            'decision' => ['required']
            
        ]);
        if(var_dump(filter_var($decision, FILTER_VALIDATE_BOOLEAN))){
        $eventMember = new EventMember();
        $eventMember = save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team_Member = Team_Member::find($id);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $eventMember = EventMember::find($id);
        $request->validate([
            'decision' => ['required']
        ]);
        if (!(var_dump(filter_var($decision, FILTER_VALIDATE_BOOLEAN)))){
            $eventMember->delete();
        }
    }
}
