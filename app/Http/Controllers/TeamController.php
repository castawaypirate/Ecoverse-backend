<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;

class TeamController extends Controller
{
    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function readOne($id) {

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
            'description'   => ['required']
        ]);

        $team = new Team();

        $team->name = $request->name;
        $team->description = $request->description;

        $team->save();

        return response('Successfull creation of team')->json(
            $team->toArray()
        );
    }

    /**
     * Update the specified team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'publish' => ['boolean']
        ]);

        $team = Team::find($id);

        if (isset($request->name) && $request->name) {
            $team->name = $request->name;
        }

        if (isset($request->description) && $request->description) {
            $team->description = $request->description;
        }

        if (isset($request->publish)) {
            $team->publish = $request->publish;
        }

        return response('Successful update of team')->json(
            $team->toArray()
        );
    }

    /**
     * Remove the specified team from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
