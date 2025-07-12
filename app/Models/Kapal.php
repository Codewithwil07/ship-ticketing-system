<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kapal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kapal',
        'tipe',
        'kapasitas',
        'harga',
        'kode_kapal',
        'rute',
        'home_base',
        'status',
        'operator',
    ];

    public function jadwalKeberangkatan()
    {
        return $this->hasMany(JadwalKeberangkatan::class);
    }
}
