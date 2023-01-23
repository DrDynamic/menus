<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Menu\DestroyMenuRequest;
use App\Http\Requests\Api\Menu\IndexMenuRequest;
use App\Http\Requests\Api\Menu\ShowMenuRequest;
use App\Http\Requests\Api\Menu\StoreMenuRequest;
use App\Http\Requests\Api\Menu\UpdateManuRequest;
use App\Models\Menu;
use App\Repositories\MenuRepository;
use App\Repositories\UserRepository;
use App\Services\Permissions;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

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
     * @param StoreMenuRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function store(StoreMenuRequest $request)
    {
        return response($this->menuRepository->createMenu(array_merge(
            $request->validated(), [
                "created_by_user_id" => $request->user()->id
            ]
        )), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(ShowMenuRequest $request, Menu $menu)
    {
        return $this->menuRepository->getById($menu->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateManuRequest $request, Menu $menu)
    {
        return $this->menuRepository->updateMenu($menu, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(DestroyMenuRequest $request, Menu $menu)
    {
        return $this->menuRepository->deleteMenu($menu);
    }
}
