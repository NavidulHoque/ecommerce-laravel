<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostQueryRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PostQueryRequest $request){

        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $posts = Post::orderBy('created_at', 'desc')->paginate($limit, ["*"], "page", $page);

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $post = Post::create($validated);
        return response()->json(['message' => 'Post created successfully'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'message' => 'Post retrieved successfully',
            'data' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body'  => 'sometimes|required|string',
        ]);

        $post->update($validated);
        return response()->json(['message' => 'Post updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
