<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignable)
     * Tambahkan semua kolom yang ada di form registrasi & excel
     */
    protected $fillable = [
        'username',
        'nama',
        'kelas',
        'jurusan',
        'saldo',
        'level',
        'password',
        'card_id',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi (misal: API)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Password otomatis di-hash jika pakai Eloquent
            'saldo' => 'integer',
        ];
    }

    /**
     * Relasi ke model Topup
     */
    public function topups()
    {
        return $this->hasMany(Topup::class);
    }
}