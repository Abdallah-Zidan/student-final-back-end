<?php

namespace App\Repositories;

use App\Comment;
use App\Http\Resources\CommentResource;
use App\Post;

class PostReplyRepository
{
    /**
     * Create new comment
     *
     * @param  int $user_id
     * @param int $comment_id
     * @param string $body
     * @return respose
     */
    public function create($user_id, $comment_id, string $body)
    {
        Comment::findOrFail($comment_id)->replies()->create([
            'body' => $body,
            'user_id' => $user_id
        ]);
        return response([], 201);
    }

    /**
     * Update Reply
     *
     * @param int  $reply_id
     * @param string $body
     * @return response
     */
    public function update($reply_id, string $body)
    {
        $reply = Comment::findOrFail($reply_id);
        $reply->update(['body' => $body]);
        return response([], 204);
    }

    /**
     * Delete Romment
     *
     * @param int $reply_id
     * @return response
     */
    public function delete($reply_id)
    {
        Comment::findOrFail($reply_id)->delete();
        return response([], 204);
    }

    /**
     * Get all Post Comments
     *
     * @param int $comment_id
     * @return void
     */
    public function getAllCommentReplies($comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $replies = $comment->replies()->with(['user'])->paginate(5);
        return CommentResource::collection($replies);
    }
}
