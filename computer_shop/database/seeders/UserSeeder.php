<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userList = Permission::create(['guard_name'=>'api','name' => 'user.list']);
        $userView = Permission::create(['guard_name'=>'api','name' => 'user.view']);
        $userCreate = Permission::create(['guard_name'=>'api','name' => 'user.create']);
        $userUpdate = Permission::create(['guard_name'=>'api','name' => 'user.update']);
        $userDelete = Permission::create(['guard_name'=>'api','name' => 'user.delete']);

        $adminRole = Role::create(['guard_name'=>'api','name' => 'admin']);
        $adminRole->givePermissionTo([
            $userCreate,
            $userList,
            $userView,
            $userUpdate,
            $userDelete
        ]);

        $admin = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole($adminRole);
        $admin->givePermissionTo([
            $userCreate,
            $userList,
            $userView,
            $userUpdate,
            $userDelete
        ]);

        $user = User::create([
            'name' => 'user',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $userRole = Role::create(['guard_name'=>'api','name' => 'user']);
        $user->assignRole($userRole);
        $user->givePermissionTo([
            $userList,
        ]);





        $vender = User::create([
            'name' => 'vender',
            'username' => 'vender',
            'email' => 'vender@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $venderRole = Role::create(['guard_name'=>'api','name' => 'vender']);
        $vender->assignRole($venderRole);
        $vender->givePermissionTo([
            $userCreate,
            $userList,
            $userView,
            $userUpdate,
            $userDelete
        ]);





    }
}
