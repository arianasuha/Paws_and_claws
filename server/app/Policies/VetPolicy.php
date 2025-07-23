<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vet;
use Illuminate\Auth\Access\Response;

class VetPolicy
{
    /**
     * Determine whether the user can bypass policy checks (e.g., for an admin).
     * This method runs BEFORE any other policy methods.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->is_admin) {
            return true; // Admins can do anything
        }
        return null; // Let the other policy methods handle it
    }

    /**
     * Determine whether the user can view any vet models.
     * Any authenticated user can view the list of vets.
     */
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can view the specified vet model.
     * Any authenticated user can view a specific vet's profile.
     */
    public function view(User $user, Vet $vet): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can create vet models.
     * A user who is marked as a vet AND doesn't already have a vet profile can create one.
     */
    public function create(User $user): bool
    {
        return $user->is_vet && !$user->vet()->exists();
    }

    /**
     * Determine whether the user can update the specified vet model.
     * Only the owner of the vet profile or an admin can update it.
     */
    public function update(User $user, Vet $vet): bool
    {
        return $user->id === $vet->user_id;
    }

    /**
     * Determine whether the user can delete the specified vet model.
     * Only the owner of the vet profile or an admin can delete it.
     */
    public function delete(User $user, Vet $vet): bool
    {
        return $user->id === $vet->user_id;
    }
}