<?php

namespace Tests\Feature\Api;

use App\Models\Menu;
use App\Models\User;
use App\Services\Permissions;
use Tests\TestCase;

class MenuControllerTest extends TestCase
{
    const BASE_URL = "/api/menus";

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

    public function test_indexReturnsOwnMenus()
    {
        $user = $this->makeUserWithRole(Permissions::ROLE_USER);
        $this->actingAs($user);

        $menus_see = Menu::factory(25)->create([
            "created_by_user_id" => $user->id
        ]);

        $menus_dont_see = Menu::factory(25)->create([
            "created_by_user_id" => User::factory()->create()->id
        ]);

        $response = $this->json(
            'GET',
            self::BASE_URL
        )
            ->assertOk();

        $response->assertJson($menus_see->toArray());
        $response->assertDontSee($menus_dont_see);

        foreach ($response->json() as $menu) {
            $this->assertDatabaseHas(Menu::TABLE, $menu);
        }
    }

    public function test_indexReturns401WhenUnauthenticated()
    {
        $this->json(
            'GET',
            self::BASE_URL
        )->assertStatus(401);
    }

    public function test_indexNeedsPermission()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->json('GET',
            self::BASE_URL,
        )->assertStatus(403);
    }

    public function test_storeCreatesMenu()
    {
        $this->actingAsAdmin();
        $menuData = Menu::factory()->make()->toArray();
        $this->json('POST', self::BASE_URL, $menuData)
            ->assertCreated()
            ->assertJson($menuData);
        $this->assertDatabaseHas(Menu::TABLE, $menuData);

        $menuData = Menu::factory()->make()->toArray();
        unset($menuData['image_url']);
        $this->json('POST', self::BASE_URL, $menuData)
            ->assertCreated()
            ->assertJson($menuData);
        $this->assertDatabaseHas(Menu::TABLE, $menuData);


        $this->json('POST', self::BASE_URL, [])
            ->assertUnprocessable();
    }

    public function test_storeNeedsPermission()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->json('POST',
            self::BASE_URL,
            []
        )->assertStatus(403);
    }

    public function test_storeReturns401WhenUnauthenticated()
    {
        $this->json(
            'POST',
            self::BASE_URL
        )->assertStatus(401);
    }

    public function test_showReturnsMenu()
    {
        $this->actingAsAdmin();

        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();
        $this->json('GET', self::BASE_URL . "/{$menu->id}")
            ->assertOk()
            ->assertJson($menu->toArray());


        $user = $this->makeUserWithRole(Permissions::ROLE_USER);
        $this->actingAs($user);

        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();
        $this->json('GET', self::BASE_URL . "/{$menu->id}")
            ->assertForbidden();


        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $user->id
        ])->create();
        $this->json('GET', self::BASE_URL . "/{$menu->id}")
            ->assertOk()
            ->assertJson($menu->toArray());
    }

    public function test_showNeedsPermission()
    {
        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->json('GET',
            self::BASE_URL . "/{$menu->id}"
        )->assertStatus(403);
    }

    public function test_showReturns401WhenUnauthenticated()
    {
        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $this->json(
            'GET',
            self::BASE_URL . "/{$menu->id}"
        )->assertStatus(401);
    }

    public function test_updateModifiesMenu()
    {
        $this->actingAsAdmin();

        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $this->json('PUT', self::BASE_URL . "/{$menu->id}", [
            'name'      => 'Lorem Ipsum',
            'image_url' => 'http://ipsum.dolor/sit.amet'
        ])->assertOk()
            ->assertJsonPath('name', 'Lorem Ipsum')
            ->assertJsonPath('image_url', 'http://ipsum.dolor/sit.amet');
        $this->assertDatabaseHas(Menu::TABLE, [
            'id'        => $menu->id,
            'name'      => 'Lorem Ipsum',
            'image_url' => 'http://ipsum.dolor/sit.amet'
        ]);

        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $this->json('PUT', self::BASE_URL . "/{$menu->id}", [
            'name' => 'Lorem Ipsum',
        ])->assertOk()
            ->assertJsonPath('name', 'Lorem Ipsum')
            ->assertJsonPath('image_url', $menu->image_url);
        $this->assertDatabaseHas(Menu::TABLE, [
            'id'        => $menu->id,
            'name'      => 'Lorem Ipsum',
            'image_url' => $menu->image_url
        ]);


        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $this->json('PUT', self::BASE_URL . "/{$menu->id}", [
            'image_url' => 'http://ipsum.dolor/sit.amet'
        ])->assertOk()
            ->assertJsonPath('name', $menu->name)
            ->assertJsonPath('image_url', 'http://ipsum.dolor/sit.amet');
        $this->assertDatabaseHas(Menu::TABLE, [
            'id'        => $menu->id,
            'name'      => $menu->name,
            'image_url' => 'http://ipsum.dolor/sit.amet'
        ]);
    }

    public function test_updateNeedsPermission()
    {
        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->json('PUT',
            self::BASE_URL . "/{$menu->id}"
        )->assertStatus(403);
    }

    public function test_updateReturns401WhenUnauthenticated()
    {
        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $this->json(
            'PUT',
            self::BASE_URL . "/{$menu->id}"
        )->assertStatus(401);
    }


    public function test_destroyDeletesMenu(){
        $this->actingAsAdmin();

        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $this->assertDatabaseHas(Menu::TABLE, ['id' => $menu->id]);

        $this->json('DELETE', self::BASE_URL . "/{$menu->id}")
            ->assertOk()
            ->json($menu->toArray());

        $this->assertDatabaseMissing(Menu::TABLE, ['id' => $menu->id]);
    }

    public function test_destroyNeedsPermission()
    {
        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->json('DELETE',
            self::BASE_URL . "/{$menu->id}"
        )->assertStatus(403);
    }

    public function test_destroyReturns401WhenUnauthenticated()
    {
        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])->create();

        $this->json(
            'DELETE',
            self::BASE_URL . "/{$menu->id}"
        )->assertStatus(401);
    }

}
