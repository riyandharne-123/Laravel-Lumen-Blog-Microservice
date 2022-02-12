<?php

namespace App\Http\Services;

//models
use App\Models\Comment;

class CommentHelper
{

    public function getAllComments()
    {
        $comments = Comment::orderBy('created_at', 'desc')
        ->get()
        ->groupBy('post_id');

        return $comments;
    }

    public function getAllCommentsForPost($post_id)
    {
        $comments = Comment::where('post_id', $post_id)
        ->orderBy('created_at', 'desc')
        ->get();

        return $comments;
    }

    public function createComment($request)
    {
        $comment = Comment::create([
            'user_id' => $request['user_id'],
            'post_id' => $request['post_id'],
            'text' => $request['text']
        ]);

        return $comment;
    }

}
