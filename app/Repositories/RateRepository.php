<?php

namespace App\Repositories;

use App\Comment;
use App\User;

class RateRepository
{
    public function create(Comment $comment, $rate, User $user)
    {
        $comment->rates()->attach($user, ['rate' => $rate]);
    }

    public function update(Comment $comment, $rate, User $user)
    {
        $rate_obj= $comment->rates()->find($user->id)->pivot;
        $rate_obj->rate = $rate;
        $rate_obj->save();
    }

    public function delete(Comment $comment, User $user)
    {
        $comment->rates()->detach($user);
    }
}
