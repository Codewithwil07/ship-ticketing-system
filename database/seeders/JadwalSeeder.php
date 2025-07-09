<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalKeberangkatan;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        JadwalKeberangkatan::insert([
            [
                'kapal_id' => 1,
                'tanggal_berangkat' => now()->addDays(3)->toDateString(),
                'jam_berangkat' => '08:00:00',
                'tujuan' => 'Bawean',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kapal_id' => 2,
                'tanggal_berangkat' => now()->addDays(5)->toDateString(),
                'jam_berangkat' => '14:30:00',
                'tujuan' => 'Karimun Jawa',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
