<?php

namespace Tests\Feature\Api;

use App\Models\Ingredient;
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

        $menus = Menu::factory(25)
            ->hasAttached(Ingredient::factory()->count(5), ['amount' => 2, 'unit' => 'pcs'])
            ->create([
                "created_by_user_id" => User::factory()->create()->id
            ]);

        $menus = Menu::query()
            ->with(['createdBy', 'ingredients'])
            ->get();

        $response = $this->json(
            'GET',
            self::BASE_URL
        )
            ->assertOk()
            ->assertJsonPath('*.id', $menus->pluck('id')->toArray())
            ->assertJsonPath('*.name', $menus->pluck('name')->toArray())
            ->assertJsonPath('*.image_url', $menus->pluck('image_url')->toArray());

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

        $menus = Menu::whereCreatedByUserId($user->id)
            ->with(['createdBy', 'ingredients'])
            ->get();

        $response = $this->json(
            'GET',
            self::BASE_URL
        )
            ->assertOk()
            ->assertJsonPath('*.id', $menus->pluck('id')->toArray())
            ->assertJsonPath('*.name', $menus->pluck('name')->toArray())
            ->assertJsonPath('*.image_url', $menus->pluck('image_url')->toArray());

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

        // Store All
        /** @var Menu $menu */
        $menu     = Menu::factory()->make();
        $menuData = $menu->toArray();

        $menuData['ingredients'] = Ingredient::factory(8)->create()
            ->map(function ($ingredient) {
                return array_merge($ingredient->toArray(),
                    [
                        'pivot' => [
                            'amount' => mt_rand(1, 10),
                            'unit'   => 'pieces'
                        ]
                    ]);
            })->toArray();


        $this->json('POST', self::BASE_URL, $menuData)
            ->assertCreated()
            ->assertJsonPath('name', $menu->name)
            ->assertJsonPath('image_url', $menu->image_url)
            ->assertJsonPath('created_by.id', $this->getAdmin()->id)
            ->assertJsonPath('created_by.email', $this->getAdmin()->email)
            ->assertJsonPath('ingredients.*.name', collect($menuData['ingredients'])->pluck('name')->toArray())
            ->assertJson($menuData);

        unset($menuData['ingredients']);
        $this->assertDatabaseHas(Menu::TABLE, $menuData);

        // Store Partial
        $menuData = Menu::factory()->make()->toArray();
        unset($menuData['image_url']);
        $this->json('POST', self::BASE_URL, $menuData)
            ->assertCreated()
            ->assertJson($menuData);
        $this->assertDatabaseHas(Menu::TABLE, $menuData);

        // Store Empty
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
        ])
            ->hasAttached(Ingredient::factory(8), ['amount' => 2, 'unit' => 'pcs'])
            ->create();

        $menu = Menu::whereId($menu->id)
            ->with(['createdBy', 'ingredients'])
            ->firstOrFail();

        $this->json('GET', self::BASE_URL . "/{$menu->id}")
            ->assertOk()
            ->assertJson($menu->toArray());


        $user = $this->makeUserWithRole(Permissions::ROLE_USER);
        $this->actingAs($user);

        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])
            ->hasAttached(Ingredient::factory(8), ['amount' => 3, 'unit' => 'pcs'])
            ->create();

        $menu = Menu::whereId($menu->id)
            ->with(['createdBy', 'ingredients'])
            ->firstOrFail();

        $this->json('GET', self::BASE_URL . "/{$menu->id}")
            ->assertForbidden();


        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $user->id
        ])
            ->hasAttached(Ingredient::factory(8), ['amount' => 8, 'unit' => 'pcs'])
            ->create();

        $menu = Menu::whereId($menu->id)
            ->with(['createdBy', 'ingredients'])
            ->firstOrFail();

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

        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->create();

        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])
            ->hasAttached(Ingredient::factory(8), ['amount' => 4, 'unit' => 'pcs'])
            ->create();

        $menu = Menu::whereId($menu->id)
            ->with(['createdBy', 'ingredients'])
            ->firstOrFail();

        $this->json('PUT', self::BASE_URL . "/{$menu->id}", [
            'name'        => 'Lorem Ipsum',
            'image_url'   => 'http://ipsum.dolor/sit.amet',
            'ingredients' => [array_merge($ingredient->toArray(), [
                'pivot' => [
                    'amount' => 2,
                    'unit'   => 'some'
                ]
            ])]
        ])->assertOk()
            ->assertJsonPath('name', 'Lorem Ipsum')
            ->assertJsonPath('image_url', 'http://ipsum.dolor/sit.amet')
            ->assertJsonPath('ingredients.*.id', [$ingredient->id])
            ->assertJsonPath('ingredients.*.name', [$ingredient->name])
            ->assertJsonPath('ingredients.*.pivot.amount', [2])
            ->assertJsonPath('ingredients.*.pivot.unit', ['some']);

        $this->assertDatabaseHas(Menu::TABLE, [
            'id'        => $menu->id,
            'name'      => 'Lorem Ipsum',
            'image_url' => 'http://ipsum.dolor/sit.amet'
        ]);

        /** @var Menu $menu */
        $menu = Menu::factory([
            'created_by_user_id' => $this->getAdmin()->id
        ])
            ->hasAttached(Ingredient::factory(8), ['amount' => 12, 'unit' => 'lorem'])
            ->create();

        $menu = Menu::whereId($menu->id)->with('createdBy', 'ingredients')
            ->firstOrFail();

        $this->json('PUT', self::BASE_URL . "/{$menu->id}", [
                'name'        => 'Lorem Ipsum',
                'ingredients' => [array_merge($ingredient->toArray(), [
                    'pivot' => [
                        'amount' => 4,
                        'unit'   => 'µg'
                    ]
                ])]
            ]
        )
            ->assertOk()
            ->assertJsonPath('name', 'Lorem Ipsum')
            ->assertJsonPath('image_url', $menu->image_url)
            ->assertJsonPath('ingredients.*.id', [$ingredient->id])
            ->assertJsonPath('ingredients.*.name', [$ingredient->name])
            ->assertJsonPath('ingredients.*.pivot.amount', [4])
            ->assertJsonPath('ingredients.*.pivot.unit', ['µg']);

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
            ->assertJsonPath('image_url', 'http://ipsum.dolor/sit.amet')
            ->assertJsonPath('ingredients', []);
        
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


    public function test_destroyDeletesMenu()
    {
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
