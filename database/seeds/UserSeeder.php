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
                'password' => Hash::make('32tgaF&YXVoEi*yn*F7vh^A4'),
            ],
            [
                'name' => 'Gelo',
                'email' => 'gelo@inqbyte.io',
                'role_id' => 1,
                'status_id' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('GD$Qea7YfWHlz1rI90m4WGnL'),
            ],
            [
                'name' => 'Jeff',
                'email' => 'jeff@inqbyte.io',
                'role_id' => 1,
                'status_id' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('#f4xbbs^5pEt&J^c%pNxHmz#'),
            ]
        ];
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
