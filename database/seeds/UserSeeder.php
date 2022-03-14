<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'administrator',
                'email' => 'admin@inqbyte.io',
                'role_id' => 1,
                'status_id' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('Bu*!2$NSenD3#vXVLc*VrbfS'),
            ],
            [
                'name' => 'Gelo',
                'email' => 'gelo@inqbyte.io',
                'role_id' => 1,
                'status_id' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('mWcGZ$yUuBZpzG^LJznQ9rN9'),
            ],
            [
                'name' => 'Jeff',
                'email' => 'jeff@inqbyte.io',
                'role_id' => 1,
                'status_id' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('&d3H^sgF5f4QUBE%*djNJZx9'),
            ]
        ];
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
