<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FieldTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any field types.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }
}
