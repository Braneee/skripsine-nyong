<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Origin;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    public function index() {
        // Menampilkan semua data gudang
        return response()->json(Origin::all());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_active' => 'boolean'
        ]);

        // Jika gudang baru di-set aktif, nonaktifkan gudang lama
        if (isset($data['is_active']) && $data['is_active']) {
            Origin::where('is_active', true)->update(['is_active' => false]);
        }

        $origin = Origin::create($data);
        return response()->json(['message' => 'Gudang berhasil ditambahkan', 'data' => $origin], 201);
    }
}
