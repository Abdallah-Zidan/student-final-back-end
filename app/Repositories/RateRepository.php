<?php

namespace App\Repositories;

use App\Comment;
use App\User;

class RateRepository
{
    /**
     * Create a rate related to the given user and comment.
     *
     * @param \App\User $user The user object.
     * @param \App\Comment $comment The comment object.
     * @param array $data The rate data.
     *
     * @return void
     */
    public function create(User $user, Comment $comment, $data)
    {
        $comment->rates()->attach($user, $data);
    }

    /**
     * Update an existing rate.
     *
     * @param mixed $rate The rate object.
     * @param array $data The rate data.
     *
     * @return void
     */
    public function update($rate, array $data)
    {
        $rate->pivot->rate = $data['rate'];
        $rate->pivot->save();
    }

    /**
     * Delete an existing rate.
     *
     * @param \App\User $user The user object.
     * @param \App\Comment $comment The comment object.
     *
     * @return void
     */
    public function delete(User $user, Comment $comment)
    {
        $comment->rates()->detach($user);
    }
}