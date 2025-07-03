<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // <-- Penting untuk UUID
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, HasUuids; // <-- Tambahkan HasUuids di sini

    /**
     * Atribut yang boleh diisi secara massal.
     */
    protected $fillable = [
        'customer_name',
        'customer_address',
        'gender',
        'birthday_date',
    ];

    /**
     * Casting tipe data untuk atribut.
     */
    protected $casts = [
        'birthday_date' => 'date',
    ];

    /**
     * Relasi: Satu Customer bisa memiliki banyak Penjualan (Sales).
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}