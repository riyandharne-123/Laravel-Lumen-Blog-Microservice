<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Validator;

//services
use App\Http\Services\PostHelper;

class PostController extends Controller
{
    protected $helper;

    public function __construct(PostHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getPosts(Request $request)
    {
        return $this->helper->getAllPosts($request->all());
    }

    public function getUsersPosts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        $user_id = $request->query('user_id');

        $posts = Redis::get('user_posts_' . $user_id);
        $posts = json_decode($posts);

        if(!$posts) {
            $posts = $this->helper->getUsersPosts($request->all());
            Redis::set('user_posts' . $user_id, $posts);
        }

        return $posts;
    }

    public function getPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        $post_id = $request->query('post_id');

        $post = Redis::get('post_' . $post_id);
        $post = json_decode($post);

        if(!$post) {
            $post = $this->helper->getPost($post_id);

            if(!$post) {
                return response()->json([
                    'error' => 'Post does not exist.'
                ], 401);
            }

            Redis::set('post_' . $post_id, $post);
        }

        return response()->json($post, 200);
    }

    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        $post = $this->helper->createPost($request->all());
        return response()->json($post, 200);
    }
}
