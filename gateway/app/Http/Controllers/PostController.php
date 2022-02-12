<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $http;
    protected $post_url;
    protected $comment_url;

    public function __construct()
    {
        $this->http = new Http();
        $this->post_url = env('POST_SERVICE_URL');
        $this->comment_url = env('COMMENT_SERVICE_URL');
    }

    public function getAllPosts(Request $request)
    {
        $posts = $this->http::get($this->post_url . '/getAllPosts', [
            'order_direction' => $request->query('order_direction'),
            'order_column' => $request->query('order_column')
        ]);

        $comments = $this->http::get($this->comment_url . '/getAllComments');
        $comments = $comments->json();

        $posts = collect($posts->json())->transform(function ($post) use ($comments) {
            $post['comments'] = isset($comments[$post['id']]) ? $comments[$post['id']] : null;
            return $post;
        });

        return response($posts, 200);
    }

    public function getUserPosts(Request $request)
    {
        $posts = $this->http::get($this->post_url . '/getUsersPosts', [
            'user_id' => $request->query('user_id'),
            'order_direction' => $request->query('order_direction'),
            'order_column' => $request->query('order_column')
        ]);

        $comments = $this->http::get($this->comment_url . '/getAllComments');
        $comments = $comments->json();

        $posts = collect($posts->json())->transform(function ($post) use ($comments) {
            $post['comments'] = isset($comments[$post['id']]) ? $comments[$post['id']] : null;
            return $post;
        });

        return response($posts, 200);
    }


    public function getPost(Request $request)
    {
        $post = $this->http::get($this->post_url . '/getPost', [
            'post_id' => $request->query('post_id')
        ]);

        if($post->clientError()) {
            return response($post->json(), 401);
        }

        $post = $post->json();

        $comments = $this->http::get($this->comment_url . '/getAllCommentsForPost', [
            'post_id' => $post['id']
        ]);

        $comments = $comments->json();
        $post['comments'] = isset($comments) && sizeof($comments) > 0 ? $comments : null;

        return response($post, 200);
    }

    public function createPost(Request $request)
    {
        $post = $this->http::post($this->post_url . '/createPost', [
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image
        ]);

        if($post->clientError()) {
            return response($post->json(), 401);
        }

        return response($post->json(), 200);
    }
}
