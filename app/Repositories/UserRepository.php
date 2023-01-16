<?php

namespace App\Repositories;

use App\Services\Permissions;

class UserRepository
{
    public function userCan($user, ...$permissions)
    {
        $permissions = $this->getPermissionsForUser($user);

        foreach ($permissions as $permission) {
            if (!$permissions->contains($permission)) {
                return false;
            }
        }
        return true;
    }

    public function userCanSome($user, string ...$permissions)
    {
        $permissions = $this->getPermissionsForUser($user);

        foreach ($permissions as $permission) {
            if ($permissions->contains($permission)) {
                return true;
            }
        }
        return false;
    }

    public function getPermissionsForUser($user)
    {
        if (!$user) {
            return collect(Permissions::ROLES[Permissions::ROLE_UNAUTHENTICATED]);
        }

        return $user->permissions;
    }
}
