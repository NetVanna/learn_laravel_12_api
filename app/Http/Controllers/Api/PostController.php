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
        $user = auth()->user();
        $posts = Post::with('author')->where('author_id', $user->id)->get();

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

        $data["author_id"] = auth()->user()->id;

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
        $user = auth()->user();
        $post = Post::with('author')->where('author_id', $user->id)->find($id);

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
        $user = auth()->user();
        $post = Post::with('author')->where('author_id', $user->id)->find($id);

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
        $user = auth()->user();
        $post = Post::with('author')->where('author_id', $user->id)->find($id);

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
