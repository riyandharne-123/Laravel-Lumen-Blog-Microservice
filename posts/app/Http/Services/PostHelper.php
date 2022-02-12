<?php

namespace App\Http\Services;

//models
use App\Models\Post;

class PostHelper
{

    public function getAllPosts($request)
    {
        $orderDirection = isset($request['order_direction']) ? $request['order_direction'] : 'desc';
        $orderColumn = isset($request['order_column']) ? $request['order_column'] : 'created_at';

        $posts = Post::orderBy($orderColumn, $orderDirection)->get();
        return $posts;
    }

    public function getUsersPosts($request)
    {
        $orderDirection = isset($request['order_direction']) ? $request['order_direction'] : 'desc';
        $orderColumn = isset($request['order_column']) ? $request['order_column'] : 'created_at';

        $posts = Post::where('user_id', $request['user_id'])->orderBy($orderColumn, $orderDirection)->get();
        return $posts;
    }

    public function getPost($id)
    {
        $post = Post::find($id);
        return $post;
    }

    public function createPost($request)
    {
        $post = Post::create([
            'user_id' => $request['user_id'],
            'title' => $request['title'],
            'description' => $request['description'],
            'image' => $request['image']
        ]);

        return $post;
    }

}
