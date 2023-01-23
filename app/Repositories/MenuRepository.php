<?php

namespace App\Repositories;

use App\Models\Menu;

class MenuRepository
{

    public const ENTITY_RELATIONS = [
        'createdBy',
        'ingredients'
    ];

    public const LIST_RELATIONS = [

    ];

    private IngredientsRepository $ingredientsRepository;

    public function __construct(IngredientsRepository $ingredientsRepository)
    {
        $this->ingredientsRepository = $ingredientsRepository;
    }

    public function getAll()
    {
        return Menu::with(self::LIST_RELATIONS)->get();
    }

    public function getByUserId(int $user_id)
    {
        return Menu::whereCreatedByUserId($user_id)
            ->with(self::LIST_RELATIONS)->get();
    }

    public function createMenu(array $data)
    {
        /** @var Menu $menu */
        $menu = Menu::create($data);
        $this->sync($menu, $data);

        return $this->getById($menu->id);
    }

    public function getById(int $id)
    {
        return Menu::whereId($id)
            ->with(self::ENTITY_RELATIONS)
            ->firstOrFail();
    }

    public function updateMenu(Menu $menu, array $data)
    {
        $menu->update($data);
        $this->sync($menu, $data);
        return $this->getById($menu->id);
    }

    public function deleteMenu(Menu $menu)
    {
        $menu->delete();
        return $menu;
    }

    public function sync(Menu $menu, array $data)
    {
        if (isset($data['ingredients'])) {
            $pivot = collect($data['ingredients'])->mapWithKeys(function ($ingredient, $key) {
                return [$ingredient['id'] => $ingredient['pivot']];
            })->toArray();

            $menu->ingredients()->sync($pivot);
        }
    }


}
