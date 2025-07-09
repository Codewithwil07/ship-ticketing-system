<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kapal;

class KapalSeeder extends Seeder
{
    public function run(): void
    {
        Kapal::insert([
            [
                'nama_kapal' => 'KM Bung Tomo',
                'tipe' => 'Penumpang',
                'kapasitas' => 250,
                'kode_kapal' => 'BTM-01',
                'rute' => 'Surabaya - Bawean - Gresik',
                'home_base' => 'Surabaya',
                'status' => 'aktif',
                'operator' => 'PT Pelayaran Nusantara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kapal' => 'KM Nusantara',
                'tipe' => 'Cargo',
                'kapasitas' => 100,
                'kode_kapal' => 'NSN-02',
                'rute' => 'Surabaya - Karimun Jawa - Semarang',
                'home_base' => 'Surabaya',
                'status' => 'aktif',
                'operator' => 'PT Laut Biru Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
