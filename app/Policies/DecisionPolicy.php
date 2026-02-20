<?php

namespace App\Policies;

use App\Models\Decision;
use App\Models\User;

class DecisionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Decision $decision): bool
    {
        return $user->id === $decision->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Decision $decision): bool
    {
        return $user->id === $decision->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Decision $decision): bool
    {
        return $user->id === $decision->user_id;
    }
}
