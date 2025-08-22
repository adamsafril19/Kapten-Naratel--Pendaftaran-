<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $units = Unit::orderBy('created_at', 'asc')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $units->items(),
            'meta' => [
                'current_page' => $units->currentPage(),
                'last_page' => $units->lastPage(),
                'per_page' => $units->perPage(),
                'total' => $units->total(),
                'from' => $units->firstItem(),
                'to' => $units->lastItem(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('API Store Unit: Menerima permintaan untuk menambah unit baru.', ['data' => $request->all()]);

        $kode = strtoupper($request->kode_unit);

        $exists = DB::table('units')
            ->whereRaw('LOWER(kode_unit) = ?', [strtolower($kode)])
            ->exists();

        if ($exists) {
            return response()->json(['status' => 'error', 'message' => 'Kode unit sudah digunakan.'], 422);
        }

        $request->validate([
            'nama_unit' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:50',
        ]);

        try {
            $unit = Unit::create([
                'nama_unit' => $request->nama_unit,
                'kode_unit' => $kode,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan unit baru: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan unit.'], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Unit berhasil ditambahkan',
            'data'    => $unit,
        ], 201);
    }

    public function show($id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json(['status' => 'error', 'message' => 'Unit tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $unit]);
    }

    public function update(Request $request, $id)
    {
        Log::info('API Update Unit: Menerima permintaan untuk memperbarui unit.', ['id' => $id, 'data' => $request->all()]);

        $unit = Unit::find($id);
        if (!$unit) {
            return response()->json(['status' => 'error', 'message' => 'Unit tidak ditemukan'], 404);
        }

        $kode = strtoupper($request->kode_unit);

        $exists = DB::table('units')
            ->whereRaw('LOWER(kode_unit) = ?', [strtolower($kode)])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json(['status' => 'error', 'message' => 'Kode unit sudah digunakan.'], 422);
        }

        $request->validate([
            'nama_unit' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:50',
        ]);

        $unit->update([
            'nama_unit' => $request->nama_unit,
            'kode_unit' => $kode,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Unit berhasil diperbarui',
            'data'    => $unit
        ]);
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json(['status' => 'error', 'message' => 'Unit tidak ditemukan'], 404);
        }

        $unit->delete();

        return response()->json(['status' => 'success', 'message' => 'Unit berhasil dihapus']);
    }

    public function all()
    {
        $allUnits = Unit::orderBy('kode_unit')->get();

        return response()->json([
            'status' => 'success',
            'data' => $allUnits
        ]);
    }
}
