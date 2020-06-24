<?php

namespace App\Http\Controllers;

use App\Event;
use App\Post;
use App\Comment;
use Auth;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['show', 'index']
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Event::all()->orderBy('created_at', 'desc'));
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
            'title' => 'required',
            'content'=>'required',
            'start'=>'required',
            'end'=>'required',
            'place'=>'required',
        ]);

        $post = new Post([
            'author_id' => Auth::user()->id,
            'title' => $request->get('title'),
            'content'=>$request->get('content'),
            'public'=>$request->get('public') == 'true' ? 1 : 0,
            'image'=>$request->get('image'),
            'team_id'=>$request->get('team_id')
        ]);
        $post->save();

        $event = new Event([
            'start'=>$request->get('start'),
            'end'=>$request->get('end'),
            'place'=>$request->get('place'),
            'post_id'=>$post->id
        ]);
        $event->save();
        $post->event_id = $event->id;


        $post->save();
        return response()->json(['message' => "Event successfully created"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth('api')->user();
        if ($user) {
            $event = Event::whereHas('posts', function($q) use ($user) {
                $q->where('posts.author_id', $user->id)->orWhere('posts.public', 1);
            });
        } else {
            $event = Event::whereHas('posts', function($q) {
                $q->where('posts.public', 1);
            });
        }
        return response()->json($event->find($id));
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
            'title'=>'required',
            'content'=>'required',
            'start'=>'required',
            'end'=>'required',
            'place'=>'required',
        ]);

        $event = Event::find($id);
        $post = Post::find($event->post_id);

        if(Auth::user()->id == $post->author_id) {

            $post->content = $request->get('content');
            $post->title = $request->get('title');
            if ($request->has('public')) {
                $post->public = $request->public == 'true' ? 1 : 0;
            }
            if ($request->has('image')) {
                $post->image = $request->get('image');
            }
            if ($request->has('team_id')) {
                $post->team_id = $request->get('team_id');
            }
            $post->save();

            $event->start = $request->get('start');
            $event->end = $request->get('end');
            $event->place = $request->get('place');
            $event->save();

            return response()->json(['message' => "Event successfully updated"]);
        }
        else
            error_log("Authorization error");

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
        $post = Post::find($event->post_id);
        if(Auth::user()->id == $post->author_id) {
            $event->delete();
            $post->delete();
            return response("Event successfully deleted!");
        }
        else
            error_log("Authorization error");
    }

    public function getUserEvents(){
        $id = Auth::user()->id;
        return response()->json(Post::where('author_id', $id)->whereNotNull('event_id')->get());
    }
}
