<?php

namespace App\Policies;

use App\Field;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FieldPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any fields.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the field.
     *
     * @param  \App\User  $user
     * @param  \App\Field  $field
     * @return mixed
     */
    public function view(User $user, Field $field)
    {
        return $field->user->id === $user->id
            ? Response::allow()
            : Response::deny('You do not own this field.');
    }

    /**
     * Determine whether the user can create fields..
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the field.
     *
     * @param  \App\User  $user
     * @param  \App\Field  $field
     * @return mixed
     */
    public function update(User $user, Field $field)
    {
        return $field->user->id === $user->id
            ? Response::allow()
            : Response::deny('You do not own this field.');
    }

    /**
     * Determine whether the user can delete the field.
     *
     * @param  \App\User  $user
     * @param  \App\Field  $field
     * @return mixed
     */
    public function delete(User $user, Field $field)
    {
        return $field->user->id === $user->id
            ? Response::allow()
            : Response::deny('You do not own this field.');
    }
}
