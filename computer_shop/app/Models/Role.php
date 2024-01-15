<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;
class Role extends BaseRole
{

    public const ADMIN = 'Admin';
    public const USER = 'User';
    public const VENDER = 'Vender';

    /**
     * @return array<string>
     */
    public static function allRoles(): array
    {
        return [
            self::ADMIN,
            self::USER,
            self::VENDER,
        ];
    }
}
