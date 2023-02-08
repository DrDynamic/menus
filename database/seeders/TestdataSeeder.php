<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestdataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::updateOrCreate([
            'name' => 'Admin'
        ], [
            'email'             => 'admin@menus.test',
            'email_verified_at' => Carbon::parse('1970-01-01 00:00:01'),
            'password'          => Hash::make('secret')
        ]);

        Menu::factory([
            "created_by_user_id" => $admin->id,
        ])
            ->count(25)
            ->hasAttached(Ingredient::factory(10), ["amount" => 250, "unit" => "ml"])
            ->create();
    }
}
