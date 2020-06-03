<?php

namespace App\Repositories;

use App\Comment;
use App\User;

class RateRepository
{
    public function create(Comment $comment, $data)
    {
        return $comment->rates()->attach(request()->user(), $data);
    }

    public function update(Comment $comment, $data)
    {
        if ($rate_obj = $comment->rates()->find(request()->user()->id)) {
            $rate_obj->pivot->rate = $data['rate'];
            $rate_obj->pivot->save();
            return $rate_obj->pivot;
        }
    }

    public function delete(Comment $comment)
    {
        $comment->rates()->detach(request()->user());
    }
}
