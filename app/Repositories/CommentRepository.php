<?php

namespace App\Repositories;

use App\Comment;
use App\Http\Resources\CommentCollection;

class CommentRepository
{
    /**
     * Create new comment
     *
     * @param  int $user_id
     * @param  $parent
     * @param string $body
     * @return respose
     */
    public function create(int $user_id, $parent, string $body)
    {
        $comment = $parent->comments()->create([
            'body' => $body,
            'user_id' => $user_id
        ]);
        return response(['data' => ['reply' => ['id' => $comment->id]]], 201);
    }

    /**
     * Update Comment
     *
     * @param Comment  $comment
     * @param string $body
     * @return response
     */
    public function update(Comment $comment,  string $body)
    {
        $comment->update(['body' => $body]);
        return response([], 204);
    }

    /**
     * Delete Comment
     *
     * @param Comment $comment
     * @return response
     */
    public function delete(Comment $comment)
    {
        $comment->delete();
        return response([], 204);
    }

    /**
     * Get all Post Comments
     *
     * @param  $parent
     * @return CommentResource::collection
     */
    public function getAllComments($parent)
    {
        $comments = $parent->comments()->with(['user', 'replies', 'replies.user'])->paginate(10);
        return new CommentCollection($comments);
    }
}
