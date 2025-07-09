<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemesanan;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        Pemesanan::create([
            'user_id' => 2,
            'jadwal_id' => 1,
            'nama_penumpang' => 'Budi Santoso',
            'jumlah_tiket' => 2,
            'total_harga' => 200000,
            'status' => 'lunas',
        ]);
    }
}
