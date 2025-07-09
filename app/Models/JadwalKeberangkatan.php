<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalKeberangkatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kapal_id',
        'tanggal_berangkat',
        'jam_berangkat',
        'tujuan',
        'status',
    ];

    public function kapal()
    {
        return $this->belongsTo(Kapal::class);
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'jadwal_id');
    }
}
