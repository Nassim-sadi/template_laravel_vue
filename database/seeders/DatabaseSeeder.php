<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(100)->create();

        User::factory()->create([
            'firstname' => 'nassim',
            'lastname' => 'sadi',
            'email' => 'nacimbreeze@gmail.com',
            'password' => 'password',
            'image' => null,
            'status' => '1',
        ]);
    }
}
