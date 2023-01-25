<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //user seeder
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'it@browniescinta.com',
            'username' => 'itbrocin',
            'password' => bcrypt('it123456'),
        ]);

        //get all permissions
        $permissions = Permission::all();

        //get role admin
        $role = Role::find(1);

        //assign permission to role
        $role->syncPermissions($permissions);

        //assign role to user
        $user->assignRole($role);
    }
}
