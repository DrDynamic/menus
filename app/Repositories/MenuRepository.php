<?php

namespace App\Repositories;

use App\Models\Menu;

class MenuRepository
{

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
        return Menu::create($data);
    }

    public function getById(int $id)
    {
        return Menu::whereId($id)->firstOrFail();
    }

    public function updateMenu(Menu $menu, array $data)
    {
        $menu->update($data);
        return $this->getById($menu->id);
    }

    public function deleteMenu(Menu $menu)
    {
        $menu->delete();
        return $menu;
    }


}
