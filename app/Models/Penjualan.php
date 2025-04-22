<?php

// app/Models/Penjualan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi Laravel
    protected $table = 'penjualan';  // Pastikan nama tabel sesuai

    // Tentukan kolom yang dapat diisi
    protected $fillable = ['tanggal_penjualan', 'jumlah', 'total_harga', 'barang_id'];

    // Relasi dengan model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}

