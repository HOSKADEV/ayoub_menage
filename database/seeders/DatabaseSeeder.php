<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
          User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'phone' => '1234567890',
            'password' => bcrypt('password'),
          ]);

//       \App\Models\User::factory(30)->create();
    }
}
