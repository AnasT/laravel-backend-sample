<?php

namespace App\Policies;

use App\Subscriber;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SubscriberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any subscribers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the subscriber.
     *
     * @param  \App\User  $user
     * @param  \App\Subscriber  $subscriber
     * @return mixed
     */
    public function view(User $user, Subscriber $subscriber)
    {
        return $subscriber->user->id === $user->id
            ? Response::allow()
            : Response::deny('You do not own this subscriber.');
    }

    /**
     * Determine whether the user can create subscribers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the subscriber.
     *
     * @param  \App\User  $user
     * @param  \App\Subscriber  $subscriber
     * @return mixed
     */
    public function update(User $user, Subscriber $subscriber)
    {
        return $subscriber->user->id === $user->id
            ? Response::allow()
            : Response::deny('You do not own this subscriber.');
    }

    /**
     * Determine whether the user can delete the subscriber.
     *
     * @param  \App\User  $user
     * @param  \App\Subscriber  $subscriber
     * @return mixed
     */
    public function delete(User $user, Subscriber $subscriber)
    {
        return $subscriber->user->id === $user->id
            ? Response::allow()
            : Response::deny('You do not own this subscriber.');
    }
}
