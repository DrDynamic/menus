<?php

namespace App\Services;

class Permissions
{
    public const ROLE_ADMIN           = "admin";
    public const ROLE_USER            = "user";
    public const ROLE_UNAUTHENTICATED = "unauthenticated";

    public const INDEX_OWN_MENUS = 'menus.index.own';
    public const INDEX_ALL_MENUS = 'menus.index.all';


    public const PERISSIONS = [
        self::INDEX_OWN_MENUS,
        self::INDEX_ALL_MENUS,
    ];

    public const ROLES = [
        self::ROLE_ADMIN => self::PERISSIONS,
        self::ROLE_USER => [
            self::INDEX_OWN_MENUS
        ],
        self::ROLE_UNAUTHENTICATED => []
    ];
}
