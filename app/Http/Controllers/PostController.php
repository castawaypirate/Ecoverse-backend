<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Event;
use App\Like;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
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
        $posts = Post::withCount('likes')->with('event')->where('public', '1')->orderBy('created_at', 'desc')->get();
        return response()->json($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): Post
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
        ]);

        $user = Auth::user();

        $post = new Post([
            'title'=>$request->title,
            'content'=>$request->content,
            'public'=> $request->public ? 1 : 0,
            'image'=>$request->image,
            'author_id'=>$user->id,
        ]);
        $post->save();

        return $post;
    }

    public function teamPosts() {
        $user = Auth::user();
        $posts = Post::whereHas('team', function ($q) {
            $q->where('teams.public', 1);
        })->orWhereHas('team.members', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->with('team')->orderBy('created_at', 'desc');

        return response()->json($posts->get());
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
            'image' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $post = new Post([
            'author_id'=>Auth::user()->id,
            'content'=>$request->get('content'),
            'title'=>$request->get('title'),
            'public'=> $request->get('public') == 'true' ? 1 : 0
        ]);

        if ($request->has('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $post->image = asset('/images/' .$name);
        }

        $post->save();

        return response()->json(['message' => "Post successfully created"]);
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
            $post = Post::where('id', $id)->where( function ($q) use ($user) {
                $q->where('author_id', $user->id)->orWhere('public', 1);
            })->with(['comments' => function ($q) {
                return $q->where('comments.comment_id', null)->orderBy('created_at', 'desc');
            }, 'comments.comments.author' => function ($q) {
                return $q->orderBy('created_at', 'desc');
            }, 'comments.author'])->withCount('likes');
        } else {
            $post = Post::where('id', $id)->where('public', 1)->with(['comments' => function ($q) {
                return $q->where('comments.comment_id', null)->orderBy('created_at', 'desc');
            }, 'comments.comments.author' => function ($q) {
                return $q->orderBy('created_at', 'desc');
            }, 'comments.author'])->withCount('likes');
        }
        return response()->json($post->first());
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
            'image' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg|max:204'
        ]);

        $post = Post::find($id);
        if (Auth::user()->id == $post->author_id) {
            $post->content = $request->get('content');
            $post->title = $request->get('title');
            if ($request->has('public')) {
                $post->public = $request->public == 'true' ? 1 : 0;
            }
            if ($request->has('image') && $request->image) {
                $image = $request->file('image');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
                $post->image = asset('/images/' .$name);
            }
            $post->save();

            return response()->json(['message' => "Post successfully updated"]);
        }
        throw new \Exception('You cant edit this post');
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

        if (!$like->first()) {
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
        return response()->json(Post::where('author_id', $id)->where('event_id', null)->get());
    }
}
