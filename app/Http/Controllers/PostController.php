<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::active()
            ->with('user')
            ->latest('published_at')
            ->paginate(20);

        return response()->json(
            PostResource::collection($posts)
        );
    }

    /**
     * Show the form for creating a new resource.
     * Only authenticated users can access this route.
     */
    public function create(): string
    {
        return 'posts.create';
    }

    /**
     * Store a newly created resource in storage.
     * Only authenticated users can create new posts.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create(
            $request->validated() + [
                'user_id' => auth()->id(),
            ]
        );

        return response()->json(
            new PostResource($post),
            201
        );
    }

    /**
     * Display the specified resource.
     * Return 404 if the post is draft or scheduled.
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json(
            new PostResource($post->load('user'))
        );
    }

    /**
     * Show the form for editing the specified resource.
     * Only the post author can access this route.
     */
    public function edit(Post $post): string
    {
        return 'posts.edit';
    }

    /**
     * Update the specified resource in storage.
     * Only the post author can update the post.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());

        return response()->json(
            new PostResource($post)
        );
    }

    /**
     * Remove the specified resource from storage.
     * Only the post author can delete the post.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
