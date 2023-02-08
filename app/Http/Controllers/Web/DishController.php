<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Inertia\Inertia;

class DishController extends Controller
{
    public function list()
    {
        return Inertia::render('Dishes/DishesList', [
                "dishes" => Menu::with(['ingredients'])->get(),
            ]
        );
    }

    public function listTailwind()
    {
        return Inertia::render('Dishes/DishesListTailwind');
    }
}
