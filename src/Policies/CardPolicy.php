<?php

namespace Taskday\Policies;

use Taskday\Models\Card;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class CardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the project.
     *
     * @param  User  $user
     * @param  Card  $card
     * @return mixed
     */
    public function view(Model $user, Card $card)
    {
        return $card->project->ownerIs($user) || $card->project->hasMember($user) || $card->project->workspace->team_id == $user->current_team_id;
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  User  $user
     * @param  Card  $card
     * @return mixed
     */
    public function update(Model $user, Card $card)
    {
        return $card->project->ownerIs($user) || $card->project->hasMember($user) || $card->project->workspace->team_id == $user->current_team_id;
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  User  $user
     * @param  Card  $card
     * @return mixed
     */
    public function delete(Model $user, Card $card)
    {
        return $card->project->ownerIs($user) || $card->project->hasMember($user);
    }
}
