<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Hero;
use App\Models\User;

class HeroPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Hero');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Hero $hero): bool
    {
        return $user->checkPermissionTo('view Hero');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Hero');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hero $hero): bool
    {
        return $user->checkPermissionTo('update Hero');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hero $hero): bool
    {
        return $user->checkPermissionTo('delete Hero');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hero $hero): bool
    {
        return $user->checkPermissionTo('restore Hero');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hero $hero): bool
    {
        return $user->checkPermissionTo('force-delete Hero');
    }
}
