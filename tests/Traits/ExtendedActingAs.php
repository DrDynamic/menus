<?php

namespace Tests\Traits;

use App\Models\User;
use App\Services\Permissions;

trait ExtendedActingAs
{
    private $admin = null;


    public function getAdmin(): User
    {
        if ($this->admin == null) {
            /** @var User $admin */
            $this->admin = User::factory()->create();
            $this->admin->roles()->sync([Permissions::ROLE_ADMIN]);
        }

        return $this->admin;
    }

    public function actingAsAdmin()
    {
        $this->actingAs($this->getAdmin());
    }

    public function makeUserWithRole(...$permissions)
    {
        $user = User::factory()->create();
        $user->roles()->sync($permissions);
        return $user;
    }
}
