<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    use HasFactory;

    // Menentukan nama tabel (Opsional jika namanya sudah plural standar)
    protected $table = 'origins';

    // Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'is_active'
    ];

    // Casting tipe data agar menjadi angka desimal/boolean saat ditarik
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];
}
