<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'nama_penumpang',
        'jumlah_tiket',
        'total_harga',
        'status',
        'bukti_ktp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalKeberangkatan::class, 'jadwal_id');
    }
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
}
