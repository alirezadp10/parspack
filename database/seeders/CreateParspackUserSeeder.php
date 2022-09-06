<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CreateParspackUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name'  => 'parspack',
            'email' => 'parspack@example.com',
        ];

        if (User::where($data)->exists()) {
            return;
        }

        User::factory()->create(array_merge($data, [
            'password' => bcrypt($pass = Str::random(10))
        ]));

        dump("You password is: $pass");
    }
}
