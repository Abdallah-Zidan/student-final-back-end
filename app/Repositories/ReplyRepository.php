<?php

namespace App\Repositories;

use App\Comment;
use App\Http\Resources\CommentResource;

class ReplyRepository
{
    /**
     * Create new comment
     *
     * @param  int $user_id
     * @param int $comment_id
     * @param string $body
     * @return respose
     */
    public function create($user_id, $comment, string $body)
    {
        $comment->replies()->create([
            'body' => $body,
            'user_id' => $user_id
        ]);
        return response(['data' => ['replay' => ['id' => $comment->id]]], 201);
    }

    /**
     * Update Reply
     *
     * @param Comment  $reply
     * @param string $body
     * @return response
     */
    public function update($reply, string $body)
    {
        $reply->update(['body' => $body]);
        return response([], 204);
    }

    /**
     * Delete Romment
     *
     * @param int $reply_id
     * @return response
     */
    public function delete($reply)
    {
        $reply->delete();
        return response([], 204);
    }

    /**
     * Get all Post Comments
     *
     * @param Comment $comment
     * @return CommentResource::collection
     */
    public function getAllCommentReplies($comment)
    {
        $replies = $comment->replies()->with(['user'])->paginate(5);
        return CommentResource::collection($replies);
    }
}
