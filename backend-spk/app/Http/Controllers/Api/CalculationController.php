<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Origin;
use App\Models\Destination;
use App\Models\CriteriaWeight;
use App\Services\HaversineService;
use App\Services\EdasService;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    protected $haversineService;
    protected $edasService;

    // Inject Services (Memanggil tukang hitung yang sudah dibuat)
    public function _construct(HaversineService $haversineService, EdasService $edasService) {
        $this->haversineService = $haversineService;
        $this->edasService = $edasService;
    }

    public function calculateEdas() {
        // 1. Ambil data gudang (origin) yang sedang aktif
        $origin = origin::where('is_active', true)->first();
        if (!$origin) {
            return response()->json(['message' => 'Tidak ada gudang / sumber bantuan aktif'], 400);
        }

        // 2. Ambil Semua Posko Terdampak (Destinations)
        $destinations = Destination::all();
        if ($destinations->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data posko terdampak'], 400);
        }

        // 3. Ambil Bobot Kriteria
        $criteria = CriteriaWeight::all()->keyBy('code');
        if ($criteria->count() < 3) {
            return response()->json(['message' => 'Konfigurasi bobot kriteria belum lengkap.'], 400);
        }

        // Susun array bobot agar rapi
        $weights = [
            'c1' => (float) $criteria['C1']->weight,
            'c2' => (float) $criteria['C2']->weight,
            'c3' => (float) $criteria['C3']->weight
        ];

        // 4. Hitung jarak haversine (C3) untuk setiap posko
        foreach ($destinations as $dest) {
            $distance = $this->haversineService->calculateDistance(
                $origin->latitude,
                $origin->longitude,
                $dest->latitude,
                $dest->longitude
            );
            // Sisipkan hasil jarak ke objek posko sebagai nilai C#
            $dest->c3_distance = $distance;
        }

        // 5. Eksekusi perhitungan EDAS
        $result = $this->edasService->calculate($destinations, $weights);

        // 6. Kembalikan response JSON ke frontend
        return response()->json([
            'message' => 'Perhitungan berhasil',
            'origin' => $origin,
            'weights' => $weights,
            'data' => $result
        ], 200);
    }
}
