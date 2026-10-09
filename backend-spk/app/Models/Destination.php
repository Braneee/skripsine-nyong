<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'destinations';

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'c1_population',
        'c2_urgency'
    ];

    protected $cast = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'c1_population' => 'integer',
        'c2_urgency' => 'integer'
    ];
}
