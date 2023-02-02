<?php

namespace App\Services;

class Permissions
{
    public const ROLE_ADMIN           = "admin";
    public const ROLE_USER            = "user";
    public const ROLE_UNAUTHENTICATED = "unauthenticated";

    public const        INDEX_OWN_MENUS = 'menu.index.own';
    public const        INDEX_ALL_MENUS = 'menu.index.all';
    public const        STORE_MENU      = "menu.store";
    const               UPDATE_MENU     = "menu.update";
    const               DESTROY_MENU    = "menu.destroy";

    const        INDEX_INGREDIENTS = "ingredient.index";
    const        STORE_INGREDIENT  = "ingredient.store";

    public const PERISSIONS = [
        self::INDEX_OWN_MENUS,
        self::INDEX_ALL_MENUS,
        self::STORE_MENU,
        self::UPDATE_MENU,
        self::DESTROY_MENU,

        self::INDEX_INGREDIENTS,
        self::STORE_INGREDIENT,
    ];

    public const ROLES = [
        self::ROLE_ADMIN           => self::PERISSIONS,
        self::ROLE_USER            => [
            self::INDEX_OWN_MENUS
        ],
        self::ROLE_UNAUTHENTICATED => []
    ];
}
