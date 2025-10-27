<?php

namespace Enraiged\Builders\Secure;

use App\Enums\Roles;
use Illuminate\Support\Facades\Auth;

trait RoleAssertions
{
    /**
     *  Assert a user is authenticated.
     *
     *  @return bool
     */
    protected function assertIsAdministrator(): bool
    {
        return Auth::check() && Auth::user()->role->is(Roles::Administrator);
    }

    /**
     *  Assert a minimum role match.
     *
     *  @param  object  $secure
     *  @return bool
     */
    protected function assertRoleAtLeast($secure): bool
    {
        $secure = (object) $secure;

        $role = Roles::find($secure->role);

        return Auth::check() && Auth::user()->role
            ? Auth::user()->role->atLeast($role)
            : false;
    }

    /**
     *  Assert a role match.
     *
     *  @param  object  $secure
     *  @return bool
     */
    protected function assertRoleIs($secure): bool
    {
        $secure = (object) $secure;

        $role = Roles::find($secure->role);

        return Auth::check() && Auth::user()->role
            ? Auth::user()->role->is($role)
            : false;
    }

    /**
     *  Assert a role does not match.
     *
     *  @param  object  $secure
     *  @return bool
     */
    protected function assertRoleIsNot($secure): bool
    {
        $secure = (object) $secure;

        $role = Roles::find($secure->role);

        return Auth::check() && Auth::user()->role
            ? Auth::user()->role->isNot($role)
            : false;
    }
}
