<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BarangImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Simpan setiap baris Excel ke database
     */
    public function model(array $row)
    {
        return new Barang([
            'nama_barang' => trim($row['nama_barang']),
            'harga'       => (int) $row['harga'],
            'kode'        => trim($row['kode']),
            'stok'        => (int) $row['stok'],
        ]);
    }

    /**
     * Validasi per baris Excel
     */
    public function rules(): array
    {
        return [
            '*.nama_barang' => 'required|string|max:255',
            '*.kode'        => 'required|unique:barangs,kode',
            '*.harga'       => 'required|numeric|min:0',
            '*.stok'        => 'required|integer|min:0',
        ];
    }

    /**
     * Pesan error custom (Bahasa Indonesia)
     */
    public function customValidationMessages(): array
    {
        return [
            '*.nama_barang.required' => 'Nama barang wajib diisi',
            '*.nama_barang.max'      => 'Nama barang maksimal 255 karakter',
            '*.kode.required'        => 'Kode barang wajib diisi',
            '*.kode.unique'          => 'Kode barang sudah terdaftar',
            '*.harga.required'       => 'Harga wajib diisi',
            '*.harga.numeric'        => 'Harga harus berupa angka',
            '*.stok.required'        => 'Stok wajib diisi',
            '*.stok.integer'         => 'Stok harus berupa angka bulat',
        ];
    }
}
