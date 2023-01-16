<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Services\Permissions;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Permissions::ROLES as $role => $permissions) {
            Role::firstOrCreate([
                'id' => $role
            ]);
        }
    }
}
