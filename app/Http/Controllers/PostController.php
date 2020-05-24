<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all()->collect();
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
            'author'=>'required',
            'content'=>'required',
        ]);

        $post = new Post([
            'author'=>$request->get('author'),
            'content'=>$request->get('content'),
            'public'=> $request->has('public'),
            'image'=>$request->get('image'),
            'team_id'=>$request->get('team_id')
        ]);

        $post->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        //
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
            'content'=>'required',
            'author'=>'required',
        ]);

        echo var_dump($request->get('public'));

        $post = Post::find($id);
        $post->content = $request->get('content');
        $post->author = $request->get('author');
        $post->public =$request->has('public');
        $post->image = $request->get('image');
        $post->team_id = $request->get('team_id');
        $post->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
    }
}
