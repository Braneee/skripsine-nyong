<?php

namespace App\Services;

class HaversineService {
    // Sesuai skripsi, radius rata rata bumi ditetapkan sebesar 6.371 km
    const EARTH_RADIUS = 6371;

    // Hitung jarak lingkaran besar antara dua titik (Origin -> Destination)
    public function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        // Konversi koordinat dari derajat ke radian
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        // Selisih latitude dan longitude
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        // Persamaan Haversine
        $a = sin($latDelta / 2) * sin($latDelta / 2) + cos($latFrom) * cos($latTo) * sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Jarak dalama kilometer
        $distance = self::EARTH_RADIUS * $c;

        return $distance;
    }
}