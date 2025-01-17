<?php

namespace Performing\Taskday\Policies;

use Performing\Taskday\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the project.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return mixed
     */
    public function view(Model $user, Comment $comment)
    {
        return true;
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return mixed
     */
    public function update(Model $user, Comment $comment)
    {
        return $comment->ownerIs($user);
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return mixed
     */
    public function delete(Model $user, Comment $comment)
    {
        return $comment->ownerIs($user);
    }
}
