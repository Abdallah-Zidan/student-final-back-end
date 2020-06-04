<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\User $user
     * @param mixed $parent The *Post* / *Event* / *Question* / *Tool* / *Comment* object.
     *
     * @return bool
     */
    public function viewAny(User $user, $parent)
    {
        if ($parent instanceof Comment) // Reply only
            return $user->can('viewAny', $parent->parent);

        return $user->can('view', $parent);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\Comment $comment
     *
     * @return bool
     */
    public function view(User $user, Comment $comment)
    {
        return $user->can('viewAny', [Comment::class, $comment->parent]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     * @param mixed $parent The *Post* / *Event* / *Question* / *Tool* / *Comment* object.
     *
     * @return bool
     */
    public function create(User $user, $parent)
    {
        return $user->can('view', $parent);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\Comment $comment
     *
     * @return bool
     */
    public function update(User $user, Comment $comment)
    {
        if ($comment->parent instanceof Comment) // Reply only
            return $user->can('view', $comment->parent->parent) && $user->id === $comment->user->id;

        return $user->can('view', $comment->parent) && $user->id === $comment->user->id; // This user is the comment owner
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param \App\Comment $comment
     *
     * @return bool
     */
    public function delete(User $user, Comment $comment)
    {
        if ($comment->parent instanceof Comment) // Reply only
            return $user->can('delete', $comment->parent->parent) && $user->id === $comment->user->id;

        return $user->can('delete', $comment->parent) &&
              ($user->id === $comment->parent->user->id || // This user is the post owner
               $user->id === $comment->user->id); //This user is the comment owner
    }
}