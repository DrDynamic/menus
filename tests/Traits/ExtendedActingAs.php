<?php

namespace Tests\Traits;

use App\Models\User;
use App\Services\Permissions;

trait ExtendedActingAs
{
    public function createAdmin()
    {
        /** @var User $admin */
        $admin = User::factory()->create();
        $admin->roles()->sync([Permissions::ROLE_ADMIN]);

        return $admin;
    }

    public function actingAsAdmin()
    {
        $this->actingAs($this->createAdmin());
    }
}
