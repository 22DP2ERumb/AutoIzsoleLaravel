<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CarBrandSeeder::class,
            CarModelSeeder::class,
            CarSeeder::class,
            AuctionSeeder::class,
        ]);
    }
}