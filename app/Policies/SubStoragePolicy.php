<?php

namespace App\Policies;

use App\Models\User;
use App\Models\sub_storage;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubStoragePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole([ 'admin', 'developer', 'user']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\sub_storage  $subStorage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, sub_storage $subStorage)
    {
        return $user->hasAnyRole([ 'admin', 'developer', 'user']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasAnyRole([ 'admin', 'developer']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\sub_storage  $subStorage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, sub_storage $subStorage)
    {
        return $user->hasAnyRole([ 'admin', 'developer']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\sub_storage  $subStorage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, sub_storage $subStorage)
    {
        return $user->hasAnyRole([ 'admin', 'developer']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\sub_storage  $subStorage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteBulk(User $user, sub_storage $subStorage)
    {
        return $user->hasAnyRole([ 'admin', 'developer']);
    }
}
