<?php

namespace App\Policies;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepositPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;// todo
        //return $user->can('viewAny deposit');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param \App\Models\Deposit $deposit
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Deposit $deposit)
    {
        return true;// todo
        //return $user->can('view deposit');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;// todo
        //return $user->can('create deposit');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param \App\Models\Deposit $deposit
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Deposit $deposit)
    {
        return true;// todo
        //return $user->can('update deposit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param \App\Models\Deposit $deposit
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Deposit $deposit)
    {
        return true;// todo
        //return $user->can('delete deposit');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param \App\Models\Deposit $deposit
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Deposit $deposit)
    {
        return true;// todo
        //return $user->can('restore deposit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param \App\Models\Deposit $deposit
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Deposit $deposit)
    {
        return true;// todo
        //return $user->can('forceDelete deposit');
    }
}
