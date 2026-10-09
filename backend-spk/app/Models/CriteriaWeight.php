<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaWeight extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'criteria_weights';

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'code',
        'name',
        'type',
        'weight',
    ];

    // Casting tipe data
    protected $casts = [
        'weight' => 'decimal:4',
    ];
}
