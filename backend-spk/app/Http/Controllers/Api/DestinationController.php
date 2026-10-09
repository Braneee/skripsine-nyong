<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index() {
        // Menampilkan semua posko terdampak
        return response()->json(Destination::all());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'c1_population' => 'required|integer|min:1',
            'c2_urgency' => 'required|integer|min:1|max:5' // Sesuai skripsi skala 1-5
        ]);

        $destination = Destination::create($data);
        return response()->json(['message' => 'Posko berhasil ditambahkan', 'data' => $destination], 201);
    }

    public function destroy($id) {
        $dest = Destination::find($id);
        if ($dest) {
            $dest->delete();
            return response()->json(['message' => 'Posko dihapus'], 200);
        }
        return response()->json(['message' => 'Posko tidak ditemukan'], 404);
    }
}
