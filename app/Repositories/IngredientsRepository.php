<?php

namespace App\Repositories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Collection;

class IngredientsRepository
{

    public function getAll(): Collection
    {
        return Ingredient::all();
    }

    public function createIngredient(array $data)
    {
        return Ingredient::create($data);
    }


}
