<?php

namespace App\Repositories;

use App\Comment;
use App\Event;
use App\Post;
use App\Question;
use App\User;

class CommentRepository
{
    /**
     * Get all comments related to *Post* / *Event* / *Tool* / *Question*.
     *
     * @param mixed $parent The *Post* / *Event* / *Tool* / *Question* object.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll($parent)
    {
        $comments = $parent->comments()->with([
            'user'
        ])->paginate(10);

        if ($parent instanceof Post || $parent instanceof Event)
        {
            $comments->load([
                'replies',
                'replies.user'
            ]);
        }

        if ($parent instanceof Question)
            $comments->load('rates');

        return $comments;
    }

    /**
     * Create a comment related to the given parent.
     *
     * @param \App\User $user The user object.
     * @param mixed $parent The *Post* / *Event* / *Tool* / *Question* object.
     * @param array $data The comment data.
     *
     * @return \App\Comment
     */
    public function create($parent, array $data)
    {
        return $parent->comments()->create($data + [
            'user_id' => request()->user()->id
        ]);
    }

    /**
     * Update an existing comment.
     *
     * @param \App\Comment $comment The comment object.
     * @param array $data The comment data.
     *
     * @return void
     */
    public function update(Comment $comment, array $data)
    {
        $comment->update($data);
    }

    /**
     * Delete an existing comment.
     *
     * @param \App\Comment $comment The comment object.
     *
     * @return void
     */
    public function delete(Comment $comment)
    {
        $comment->delete();
    }
}