<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nia' => '1234567890',
            'name' => 'User',
            'email' => 'user@gmail.com',
            'phone_number' => '081234567890',
            'password' => bcrypt('password'),
            'photo' => 'user.png',
            'status_user' => 1,
            'jabatan_id' => 1
        ]);
    }
}
