<?php

namespace Tests\Feature\Api;

use App\Models\Menu;
use App\Models\User;
use Tests\TestCase;

class MenuControllerTest extends TestCase
{
    const BASE_URL = "/api/menus";

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_indexReturnsAllMenus()
    {
        $this->actingAsAdmin();

        Menu::factory(25)->create([
            "created_by_user_id" => User::factory()->create()->id
        ]);

        $response = $this->json(
            'GET',
            self::BASE_URL
        )
            ->assertOk();

        foreach ($response->json() as $menu) {
            $this->assertDatabaseHas(Menu::TABLE, $menu);
        }
    }
}
