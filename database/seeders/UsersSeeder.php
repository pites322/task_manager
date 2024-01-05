<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'name'     => 'Test User 1',
            'email'    => 'test1@example.com',
            'password' => bcrypt('secret'),
        ]);

        \App\Models\User::factory()->create([
            'name'     => 'Test User 2',
            'email'    => 'test2@example.com',
            'password' => bcrypt('secret'),
        ]);

        \App\Models\User::factory()->create([
            'name'     => 'Test User 3',
            'email'    => 'test3@example.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
