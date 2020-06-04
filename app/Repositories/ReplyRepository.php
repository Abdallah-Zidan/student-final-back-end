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
     * @param \App\User $user The user object.
     * @param \App\Comment $comment The comment object.
     * @param array $data The reply data.
     *
     * @return \App\Comment
     */
    public function create(User $user, Comment $comment, array $data)
    {
        return $comment->replies()->create($data + [
            'user_id' => $user->id
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
     * Delete an existing reply.
     *
     * @param \App\Comment $reply The reply object.
     *
     * @return void
     */
    public function delete(Comment $reply)
    {
        $reply->delete();
    }
}