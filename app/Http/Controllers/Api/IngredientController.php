<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Ingredient\IndexIngredientRequest;
use App\Http\Requests\Api\Ingredient\StoreIngredientRequest;
use App\Repositories\IngredientsRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IngredientController extends Controller
{

    private IngredientsRepository $ingredientsRepository;

    public function __construct(IngredientsRepository $ingredientsRepository)
    {
        $this->ingredientsRepository = $ingredientsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(IndexIngredientRequest $request)
    {
        return $this->ingredientsRepository->getAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreIngredientRequest $request
     * @return Response
     */
    public function store(StoreIngredientRequest $request)
    {
        return $this->ingredientsRepository->createIngredient($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
