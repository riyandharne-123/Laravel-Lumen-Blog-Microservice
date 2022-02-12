<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $http;
    protected $comment_url;

    public function __construct()
    {
        $this->http = new Http();
        $this->comment_url = env('COMMENT_SERVICE_URL');
    }

    public function createComment(Request $request)
    {
        $comment = $this->http::post($this->comment_url . '/createComment', [
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'text' => $request->text
        ]);

        if($comment->clientError()) {
            return response($comment->json(), 401);
        }

        return response($comment->json(), 200);
    }
}
