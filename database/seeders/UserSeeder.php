<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
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
            'name' => 'Emīls',
            'email' => '123',
            'password' => Hash::make('123'),
        ]);

        User::create([
            'name' => 'Anna',
            'email' => 'anna@example.com',
            'password' => Hash::make('password1'),
        ]);

        User::create([
            'name' => 'Jānis',
            'email' => 'janis@example.com',
            'password' => Hash::make('password2'),
        ]);

        User::create([
            'name' => 'Laura',
            'email' => 'laura@example.com',
            'password' => Hash::make('password3'),
        ]);

        User::create([
            'name' => 'Mārtiņš',
            'email' => 'martins@example.com',
            'password' => Hash::make('password4'),
        ]);

        User::create([
            'name' => 'Zane',
            'email' => 'zane@example.com',
            'password' => Hash::make('password5'),
        ]);
    }
}
