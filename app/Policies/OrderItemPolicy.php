<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\OrderItem;
use App\Models\User;

class OrderItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any OrderItem');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrderItem $orderitem): bool
    {
        return $user->checkPermissionTo('view OrderItem');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create OrderItem');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrderItem $orderitem): bool
    {
        return $user->checkPermissionTo('update OrderItem');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderItem $orderitem): bool
    {
        return $user->checkPermissionTo('delete OrderItem');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrderItem $orderitem): bool
    {
        return $user->checkPermissionTo('restore OrderItem');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrderItem $orderitem): bool
    {
        return $user->checkPermissionTo('force-delete OrderItem');
    }
}
