<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DishController extends Controller
{
    public function list()
    {
        return Inertia::render('Dishes/DishesList'
        );
    }
}
