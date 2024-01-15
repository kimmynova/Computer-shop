<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as BasePermission;
class Permission extends BasePermission
{
    use HasFactory;


    public static array $modules = [
        'user',
        'role',
        'product',
        'category',
        'brand',
        'address',
    ];
    public static function defaultPermission(): array
    {
        $permissions = [];
        foreach (self::$modules as $module) {
            $permissions = array_merge($permissions, [
                "view_$module",
                "create_$module",
                "edit_$module",
                "remove_$module",
            ]);
        }
        return $permissions;
    }
}
