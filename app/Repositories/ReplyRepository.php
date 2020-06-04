<?php

namespace App\Repositories;

use App\Comment;
use App\User;

class ReplyRepository 
{
    /**
     * Get all replies related to comment.
     *
     * @param \App\Comment $comment The comment object.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(Comment $comment)
    {
        return $comment->replies()->with('user')->paginate(10);
    }

    /**
     * Create a reply related to the given comment
     *
     * @param \App\Comment $comment The comment object.
     * @param array $data The reply data.
     *
     * @return \App\Comment
     */
    public function create(Comment $comment, array $data)
    {
        return $comment->replies()->create($data + [
            'user_id' => request()->user()->id
        ]);
    }

    /**
     * Update an existing reply.
     *
     * @param \App\Comment $reply The reply object.
     * @param array $data The reply data.
     *
     * @return void
     */
    public function update(Comment $reply, array $data)
    {
        $reply->update($data);
    }

    /**
     * Delete re$reply
     *
     * @param Comment $reply
     * @return response
     */
    public function delete(Comment $reply)
    {
        $reply->delete();
    }
}