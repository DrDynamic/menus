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

}
