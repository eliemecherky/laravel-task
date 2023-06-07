<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create Super Admin role if doesn't exist
        $role_admin = Role::firstOrCreate(['name' => 'Super Admin']);
        $role_user = Role::firstOrCreate(['name' => 'user']);

        //
        $permissions = [

            'Users List',
            'Users Read',
            'Users Edit',
            'Users Add',
            'Users Delete',
            'Categories List',
            'Categories Read',
            'Categories Edit',
            'Categories Add',
            'Categories Delete',
            'Products List',
            'Products Read',
            'Products Edit',
            'Products Add',
            'Products Delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        // give Super Admin access to all permissions
        $role_admin->syncPermissions(Permission::all());

        //select permissions for users
        $permissions_users = [
            'Categories List',
            'Categories Read',
            'Categories Edit',
            'Categories Add',
            'Products List',
            'Products Read',
            'Products Edit',
            'Products Add',
        ];


        foreach ($permissions_users as $permission_user) {
            $role_user->givePermissionTo($permission_user);
        }

        // check if super admin user exists
        $user_admin = User::whereHas('roles', function ($q) {
            $q->where('name', 'Super Admin');
        })->first();

        if (!$user_admin) {
            $user_admin = User::create([
                'name'      => 'Super Admin',
                'email'     => 'admin@gmail.com',
                'password'  => bcrypt('admin_inkC@d$'),

            ]);
        }

        // assign super-admin role to default user
        $user_admin->assignRole($role_admin->name);

        // check if super admin user exists
        $user = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->first();

        if (!$user) {
            $user = User::create([
                'name'      => 'user',
                'email'     => 'user@gmail.com',
                'password'  => bcrypt('user_inkC@d$'),

            ]);
        }

        // assign super-admin role to default user
        $user->assignRole($role_user->name);
    }
}
