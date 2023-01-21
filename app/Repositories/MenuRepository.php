<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Models\Menu;

class MenuRepository
{

    private IngredientsRepository $ingredientsRepository;

    public function __construct(IngredientsRepository $ingredientsRepository)
    {
        $this->ingredientsRepository = $ingredientsRepository;
    }

    public function getAll()
    {
        return Menu::all();
    }

    public function getByUserId(int $user_id)
    {
        return Menu::whereCreatedByUserId($user_id)->get();
    }

    public function createMenu(array $data)
    {
        /** @var Menu $menu */
        $menu = Menu::create($data);
        $this->sync($menu, $data);

        return $menu;
    }

    public function getById(int $id)
    {
        return Menu::whereId($id)->firstOrFail();
    }

    public function updateMenu(Menu $menu, array $data)
    {
        $menu->update($data);
        $this->sync($menu);
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
            $syncLists = collect($data['ingredients'])->mutations(Menu::class, 'ingredients');

            $syncLists['create']->each(function ($item) {
                Ingredient::create($item);
            });

            $syncLists['update']->each(function ($item) {
                Ingredient::where('id', $item['id'])
                    ->first()
                    ->update($item);
            });

            $syncLists['delete']->each(function ($item) {
                Ingredient::where('id', $item['id'])->delete();
            });

        }
    }


}
