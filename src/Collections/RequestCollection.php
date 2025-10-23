<?php

namespace Enraiged\Collections;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RequestCollection extends Collection
{
    use Traits\Filled;

    /** @var  Collection  The collection of request route parameters. */
    protected $route;

    /** @var  User  The authenticated user who initiated the request. */
    protected $user;

    /**
     *  @return \Enraiged\Collections\RouteCollection
     */
    public function route(): RouteCollection
    {
        return $this->route;
    }

    /**
     *  Get the authenticated user who initiated the request.
     *
     *  @param  \Enraiged\Users\Models\User  $user
     *  @return mixed
     */
    public function user($user = null)
    {
        if ($user && $user instanceof User) {
            $this->user = $user;

            return $this;
        }

        return $this->user;
    }

    /**
     *  Create a collection from the provided Request object.
     *
     *  @param  \Illuminate\Http\Request  $request
     *  @return self
     */
    public static function From(Request $request): self
    {
        $called = get_called_class();

        $collection = new $called($request->all());
        $collection->route = RouteCollection::from($request->route());

        if (Auth::check()) {
            $collection->user = $request->user();
        }

        return $collection;
    }
}
