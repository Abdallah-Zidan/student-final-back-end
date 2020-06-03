<?php

namespace App\Repositories;

use App\Comment;
use App\Http\Resources\CommentCollection;

class CommentRepository
{

    /**
     * Get all Post Comments
     *
     * @param  $parent
     * @return CommentResource::collection
     */
    public function getAll($parent)
    {
        $comments = $parent->comments()->with(['user', 'replies', 'replies.user'])->paginate(10);
        return new CommentCollection($comments);
    }

    /**
     * Create new comment
     *
     * @param  int $user_id
     * @param  $parent post event tool question
     * @param string $body
     * @return respose
     */
    public function create($parent, array $data)
    {
        $comment = $parent->comments()->create(
            $data + [
                'user_id' => request()->user()->id
            ]
        );
        return $comment;
    }

    /**
     * Update Comment
     *
     * @param Comment  $comment
     * @param string $body
     * @return response
     */
    public function update($comment, array $data)
    {
        return $comment->update($data);
    }

    /**
     * Delete Comment
     *
     * @param Comment $comment
     * @return response
     */
    public function delete(Comment $comment)
    {
        return $comment->delete();
    }
}
