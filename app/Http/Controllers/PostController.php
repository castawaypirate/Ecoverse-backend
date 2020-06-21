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
            'content'=>'required',
            'title'=>'required',
        ]);

        $post = new Post([
            'author_id'=>Auth::user()->id,
            'content'=>$request->get('content'),
            'title'=>$request->get('title'),
            'public'=> $request->get('public'),
            'image'=>$request->get('image'),
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
        return Post::find($id)->toJson();
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
            'title'=> 'required',
        ]);

        $post = Post::find($id);
        if (Auth::user()->id == $post->author_id) {
            $post->content = $request->get('content');
            //$post->author_id = Auth::user()->id;
            $post->title = $request->get('title');
            if ($request->has('public')) {
                $post->public = $request->get('public');
            }
            if ($request->has('image')) {
                $post->image = $request->get('image');;
            }
            $post->save();

            return response("Post successfully updated!");
        }
        else
            error_log("Error Authentication");
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
        if (Auth::user()->id == $post->author_id) {
            $post->delete();
            return response("Post successfully deleted!");
        }
        else
            error_log("Error Authentication");
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

    public function getUserPosts(){
        $id = Auth::user()->id;
        return Post::where('author_id',  $id)->get()->toJson();
    }
}
