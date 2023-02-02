<?php

namespace Tests\Feature\Api;

use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\User;
use Tests\TestCase;

class IngredientControllerTest extends TestCase
{
    const BASE_URL = "/api/ingredient";

    public function test_indexReturnsAllIngredients()
    {
        $this->actingAsAdmin();

        $this->testIndex(
            self::BASE_URL,
            Ingredient::factory(),
            [],
            ['name']
        );
    }

    public function test_indexIngredientsNeedsAuthentication()
    {
        $this->testUnauthenticated('GET', self::BASE_URL);
    }

    public function test_indexIngredientsNeedsPermission()
    {
        $this->testForbidden('GET', self::BASE_URL);
    }

    public function test_storeCreatesIngredient()
    {
        $this->actingAsAdmin();

        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->make();

        $this->testStore(self::BASE_URL, [
            'name' => $ingredient->name,
        ]);
    }

    public function test_storeIngredientNeedsPermission()
    {
        $this->testForbidden('POST', self::BASE_URL);
    }

    public function test_storeIngredientNeedsAuthentication()
    {
        $this->testUnauthenticated('POST', self::BASE_URL);
    }

    public function test_showReturnsIngredient()
    {
        $this->actingAsAdmin();

        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->create();
        $this->testShow(self::BASE_URL . "/{$ingredient->id}", $ingredient->toArray());
    }

    public function test_showNeedsPermission()
    {
        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->create();
        $this->testForbidden('GET', self::BASE_URL . "/{$ingredient->id}");
    }

    public function test_showReturns401WhenUnauthenticated()
    {
        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->create();
        $this->testUnauthenticated('GET', self::BASE_URL . "/{$ingredient->id}");
    }

    public function test_updateModifiesMenu()
    {
        $this->actingAsAdmin();

        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::factory()->create();

        $this->testUpdate(self::BASE_URL . "/{$ingredient->id}", [
            
        ]);


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
