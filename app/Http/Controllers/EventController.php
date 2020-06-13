<?php

namespace App\Http\Controllers;

use App\Event;
use App\Post;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Event::all()->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response("Create Event");
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
            'author'=>'required',
            'content'=>'required',
            'start'=>'required',
            'end'=>'required',
        ]);

        $post = new Post([
            'author'=>$request->get('author'),
            'content'=>$request->get('content'),
            'public'=>$request->has('public'),
            'image'=>$request->get('image'),
            'team_id'=>$request->get('team_id')
        ]);


        $event = new Event([
            'start'=>$request->get('start'),
            'end'=>$request->get('end'),
            'place'=>$request->get('place'),
            'post_id'=>$post->id
        ]);

        $post->save();
        $event->save();
        return response("Event successfully created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Event::find($id)->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);
        return response("Edit Event");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'author'=>'required',
            'content'=>'required',
            'start'=>'required',
            'end'=>'required',
            'place'=>'required',
        ]);

        $event = Event::find($id);
        $post = Post::find($event->post_id);

        $post->content = $request->get('content');
        $post->author = $request->get('author');
        if($request->has('public')) {
            $post->public = $request->has('public');
        }
        if($request->has('image')) {
            $post->image = $request->get('image');
        }
        if($request->has('team_id')) {
            $post->team_id = $request->get('team_id');
        }
        $post->save();

        $event->start = $request->get('start');
        $event->end = $request->get('end');
        $event->place = $request->get('place');
        $event->save();

        return response("Event successfully updated!");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
        return response("Event successfully deleted!");
    }
}
