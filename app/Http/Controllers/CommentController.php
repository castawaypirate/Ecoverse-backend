<?php

namespace App\Http\Controllers;

use App\Comment;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function edit(Request $request, $comment_id) {
        $request->validate([
            'content' => 'required'
        ]);

        $user = Auth::user();
        $comment = Comment::find($comment_id);

        if ($comment->user_id != $user->id) {
            throw new \Exception("You cant edit this comment");
        }

        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'message' => 'Successful update of comment',
            'comment' => $comment
        ]);
    }

    public function createAnswer(Request $request, $comment_id) {
        $request->validate([
            'content' => 'required'
        ]);

        $user = Auth::user();
        $parentComment = Comment::find($comment_id);

        $childComment = new Comment();
        $childComment->content = $request->content;
        $childComment->comment_id = $parentComment->id;
        $childComment->author_id = $user->id;
        $childComment->post_id = $parentComment->post_id;
        $childComment->save();

        return response()->json([
            'message' => 'Successful creation of comment',
            'comment' => $childComment
        ]);
    }

    public function getAllComments($comment_id) {
        $comments = Comment::where('comment_id', $comment_id)->with(['comments', 'likes']);

        return response()->json([
            $comments->get()
        ]);
    }

    public function destroy($comment_id) {
        $user = Auth::user();
        $comment = Comment::find($comment_id);
        if ($user->id != $comment->user_id) {
            throw new \Exception('You can\'t delete this comment');
        }

        $comment->delete();

        return response()->json([
            'Successful deletion of comment'
        ]);
    }

    public function handleLike($id) {
        $user = Auth::user();
        $like = Like::where('comment_id', $id)->where('user_id', $user->id);

        if (!$like) {
            $like = new Like();
            $like->comment_id = $id;
            $like->user_id = $user->id;
            $like->save();
            $message = 'Succesfully add like to comment';
        } else {
            $like->delete();
            $message = 'Successfully remove like from comment';
        }

        return response()->json([
            'message' => $message
        ]);
    }
}
