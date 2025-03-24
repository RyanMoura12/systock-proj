<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'User 1',
            'tax_id' => '12345678910',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'User 2',
            'tax_id' => '12345678910',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'User 3',
            'tax_id' => '12345678910',
            'email' => 'user3@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'User 4',
            'tax_id' => '12345678910',
            'email' => 'user4@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'User 5',
            'tax_id' => '12345678910',
            'email' => 'user5@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
