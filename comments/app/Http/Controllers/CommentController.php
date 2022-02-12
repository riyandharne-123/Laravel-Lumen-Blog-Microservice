<?php

namespace App\Http\Controllers;

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

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        return $this->helper->getAllCommentsForPost($request['post_id']);
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
