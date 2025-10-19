<?php

namespace Enraiged\Database\Track;

trait ByUser
{
    /**
     *  @param  \Enraiged\Users\Models\User  $user
     *  @return array|null
     */
    private function byUser($user): ?array
    {
        $model = config('auth.providers.users.model');

        if ($user && $user instanceof $model) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }

        return null;
    }
}
