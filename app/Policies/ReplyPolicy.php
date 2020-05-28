<?php

namespace App\Policies;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, Comment $comment,  $parent)
    {
        return  $comment->parent->id === $parent->id;     //this comment belongs to this parent
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $reply,  $parent, Comment $comment)
    {
        return  $comment->parent->id === $parent->id       //this comment belongs to this parent
            && $reply->parent->id === $comment->id      //this reply belongs to this comment
            && $reply->user_id === $user->id;           //this user is the reply owner
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $reply,  $parent, Comment $comment)
    {

        return $comment->parent->id === $parent->id     //this comment belongs to this parent
            && $reply->parent->id === $comment->id    //this reply belongs to this comment
            && ($user->id === $parent->user_id         // this user is the parent owner
                || $user->id  === $reply->user_id);  //this user is the reply owner
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function restore(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function forceDelete(User $user, Comment $comment)
    {
        //
    }
}
