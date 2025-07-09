<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        Pembayaran::create([
            'pemesanan_id' => 1,
            'metode_pembayaran' => 'Transfer BCA',
            'bukti' => 'bukti-transfer.jpg',
            'status_verifikasi' => 'diterima',
        ]);
    }
}
