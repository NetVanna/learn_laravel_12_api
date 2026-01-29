<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Use with('author') to prevent N+1 query issues since the Resource loads the author
        $posts = Post::with('author')->get();

        return response()->json([
            "data" => PostResource::collection($posts),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $data = $request->validated();

        // Ensure you handle the case where the user might not be authenticated if this is a public API
        // For now, hardcoding as you had it
        $data["author_id"] = 1;

        $post = Post::create($data);

        return response()->json([
            "message" => "Post created successfully",
            "data" => new PostResource($post),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::with('author')->find($id);

        if (!$post) {
            return response()->json([
                "message" => "Post not found",
            ], 404);
        }

        return response()->json([
            "data" => new PostResource($post),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                "message" => "Post not found",
            ], 404);
        }

        $data = $request->validated();

        $post->update($data);

        return response()->json([
            "message" => "Post updated successfully",
            "data" => new PostResource($post),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                "message" => "Post not found",
            ], 404);
        }

        $post->delete();

        return response()->json([
            "message" => "Post deleted successfully",
        ]);
    }
}
