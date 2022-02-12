<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Validator;

//helpers
use App\Http\Services\CommentHelper;

class CommentController extends Controller
{
    protected $helper;

    public function __construct(CommentHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getAllComments()
    {
        return $this->helper->getAllComments();
    }

    public function getAllCommentsForPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer'
        ]);

        $post_id = $request->query('post_id');

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        $comments = Redis::get('post_comments_' . $post_id);
        $comments = json_decode($comments);

        if(!$comments) {
            $comments = $this->helper->getAllCommentsForPost($request->all());
            Redis::set('post_comments_' . $post_id, $comments, 'EX', 1800);
        }

        return $comments;
    }

    public function createComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'post_id' => 'required|integer',
            'text' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        $comment = $this->helper->createComment($request->all());
        return response()->json($comment, 200);
    }
}
