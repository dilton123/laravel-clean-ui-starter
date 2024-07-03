<?php

namespace Modules\Auth\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Modules\Auth\Models\User;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     *
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return $user->hasPermissionTo('manage-users');
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function view(User $user, User $model): Response|bool
    {
        if (!$user->hasRoles('super-administrator') && $model->hasRoles('super-administrator')) {
            return false;
        }
        return ($user->id === $model->id && $user->hasPermissionTo('manage-account'));
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     *
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return $user->hasPermissionTo('manage-users');
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function update(User $user, User $model): Response|bool
    {
        if (!$user->hasRoles('super-administrator') && $model->hasRoles('super-administrator')) {
            return false;
        }
        return ($user->id === $model->id && $user->hasPermissionTo('manage-account')) || $user->hasPermissionTo('manage-users');
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        if (!$user->hasRoles('super-administrator') && $model->hasRoles('super-administrator')) {
            return false;
        }
        return ($user->id === $model->id && $user->hasPermissionTo('manage-account')) || $user->hasRoles('super-administrator|administrator');
    }


    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function restore(User $user, User $model): Response|bool
    {
        return false;
    }


    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, User $model): Response|bool
    {
        return false;
    }
}
