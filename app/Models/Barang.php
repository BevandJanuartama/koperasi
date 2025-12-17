<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs'; // karena bukan jamak (default Laravel adalah 'barangs')
    protected $fillable = [
        'nama_barang',
        'harga',
        'kode',
        'stok',
    ];

    /**
     * Relasi ke detail transaksi
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
