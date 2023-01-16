<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Menu\IndexMenuRequest;
use App\Repositories\MenuRepository;
use App\Repositories\UserRepository;
use App\Services\Permissions;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    private MenuRepository $menuRepository;
    private UserRepository $userRepository;

    public function __construct(MenuRepository $menuRepository,
                                UserRepository $userRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(IndexMenuRequest $request)
    {
        if ($this->userRepository->userCan($request->user(), Permissions::INDEX_ALL_MENUS)) {
            return $this->menuRepository->getAll();
        }
        return $this->menuRepository->getByUserId($request->user()->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
