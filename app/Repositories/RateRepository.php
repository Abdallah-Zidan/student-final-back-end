<?php

namespace App\Repositories;

use App\Comment;
use App\User;

class RateRepository
{
    /**
     * Create a rate related to the given user and comment.
     *
     * @param \App\Comment $comment The comment object.
     * @param array $data The rate data.
     *
     * @return void
     */
    public function create(Comment $comment, array $data)
    {
        $comment->rates()->attach(request()->user(), $data);
    }

    /**
     * Update an existing rate.
     *
     * @param Comment $comment 
     * @param array $data The rate data.
     *
     * @return void
     */
    public function update(Comment $comment, array $data)
    {
        if ($rate_obj = $comment->rates()->find(request()->user()->id)) 
        {
            $rate_obj->pivot->rate = $data['rate'];
            $rate_obj->pivot->save();
            return $rate_obj->pivot;
        }
    }

    /**
     * Delete an existing rate.
     *
     * @param \App\User $user The user object.
     * @param \App\Comment $comment The comment object.
     *
     * @return void
     */
    public function delete(Comment $comment)
    {
        $comment->rates()->detach(request()->user());
    }
}