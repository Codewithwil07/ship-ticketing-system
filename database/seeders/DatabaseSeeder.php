<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\KapalSeeder;
use Database\Seeders\JadwalSeeder;
use Database\Seeders\PemesananSeeder;
use Database\Seeders\PembayaranSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KapalSeeder::class,
            JadwalSeeder::class,
            PemesananSeeder::class,
            PembayaranSeeder::class,
        ]);
    }
}
