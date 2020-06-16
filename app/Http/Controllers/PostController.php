<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Auth;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;
use function MongoDB\BSON\toJSON;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all()->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response("Post create page");
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
            'public'=> $request->get('public'),
            'image'=>$request->get('image'),
            'team_id'=>$request->get('team_id')
        ]);

        $post->save();

        return response("Post successfully created");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::with(['comments','likes'])->with('likes')->find($id)->toJson();
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
        return response("Post edit page");
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
            'content' => 'required',
            'author' => 'required',
        ]);

        $post = Post::find($id);
        $post->content = $request->get('content');
        $post->author = $request->get('author');
        if ($request->has('public')) {
            $post->public = $request->get('public');
        }
        if ($request->has('image')) {
            $post->image = $request->get('image');;
        }
        if ($request->has('team_id')) {
            $post->team_id = $request->get('team_id');
        }
        $post->save();

        return response("Post successfully updated!");
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
        return response("Post successfully deleted!");
    }

    public function addComment(Request $request, $post_id) {
        $request->validate([
            'content' => 'required'
        ]);

        $user = Auth::user();

        $comment = new Comment();
        $comment->author_id = $user->id;
        $comment->post_id = $post_id;
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'message' => 'Successful creation of comment',
            'comment' => $comment
        ]);
    }

    public function handleLike($id) {
        $user = Auth::user();
        $like = Like::where('post_id', $id)->where('user_id', $user->id);

        if (!$like) {
            $like = new Like();
            $like->post_id = $id;
            $like->user_id = $user->id;
            $like->save();
            $message = 'Succesfully add like to post';
        } else {
            $like->delete();
            $message = 'Successfully remove like from post';
        }

        return response()->json([
            'message' => $message
        ]);
    }
}
