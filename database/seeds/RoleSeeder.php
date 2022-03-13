<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'role' => 'Administrator',
                'slug' => 'admin',
            ],
            [
                'role' => 'Guest',
                'slug' => 'guest',
            ]
        ];
        foreach ($roles as $key => $role) {
            Role::create($role);
        }
    }
}
