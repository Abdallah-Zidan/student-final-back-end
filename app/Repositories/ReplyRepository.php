<?php

namespace App\Repositories;

use App\Comment;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\ReplyCollection;

class ReplyRepository 
{

    /**
     * Get all  replies
     *
     * @param Comment $comment
     * @return CommentCollection
     */
    public function getAll($comment)
    {
        $replies = $comment->replies()->with(['user'])->paginate(10);
        return new ReplyCollection($replies);
    }

    /**
     * Create new reply
     *
     * @param  int $user_id
     * @param int $comment_id
     * @param string $body
     * @return resposne
     */
    public function create(Comment $comment, array $data)
    {
        $reply = $comment->replies()->create(
            $data + [
                'user_id' => request()->user()->id
            ]
        );
        return $reply;
    }

    /**
     * Update Comment
     *
     * @param Comment  $comment
     * @param string $body
     * @return response
     */
    public function update($reply, array $data)
    {
        return $reply->update($data);
    }

    /**
     * Delete re$reply
     *
     * @param Comment $reply
     * @return response
     */
    public function delete(Comment $reply)
    {
        return $reply->delete();
    }
}
