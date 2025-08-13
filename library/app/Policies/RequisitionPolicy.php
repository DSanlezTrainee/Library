<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Requisition;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequisitionPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isCitizen();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Requisition $requisition): bool
    {
        return $user->isAdmin() || $requisition->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isCitizen();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Requisition $requisition): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Requisition $requisition): bool
    {
        return $user->isAdmin();
    }
}
