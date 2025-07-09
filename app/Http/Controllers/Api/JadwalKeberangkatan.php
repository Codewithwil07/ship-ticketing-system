<?php

namespace App\Http\Controllers\Api;

use App\Models\JadwalKeberangkatan;
use App\Models\Kapal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class JadwalKeberangkatanController extends Controller
{
    /**
     * 🔐 ADMIN - GET semua jadwal dengan search, filter, pagination
     */
    public function index(Request $request)
    {
        $query = JadwalKeberangkatan::with('kapal');

        if ($search = $request->query('search')) {
            $query->where('tujuan', 'like', "%$search%")
                ->orWhereHas('kapal', function ($q) use ($search) {
                    $q->where('nama_kapal', 'like', "%$search%");
                });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $perPage = $request->query('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    /**
     * 🔐 USER - GET semua jadwal tersedia tanpa pagination
     */
    public function getUser()
    {
        $data = JadwalKeberangkatan::with('kapal')
            ->where('status', 'tersedia')
            ->orderBy('tanggal_berangkat', 'asc')
            ->get();

        return response()->json($data);
    }

    /**
     * 🔐 ADMIN - CREATE jadwal baru
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'kapal_id' => 'required|exists:kapals,id',
                'tanggal_berangkat' => 'required|date',
                'jam_berangkat' => 'required|date_format:H:i',
                'tujuan' => 'required|string',
                'status' => 'required|in:tersedia,penuh,batal',
            ]);

            $jadwal = JadwalKeberangkatan::create($data);
            return response()->json($jadwal, 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menambah jadwal'], 500);
        }
    }

    /**
     * 🔐 ADMIN - SHOW detail
     */
    public function show($id)
    {
        try {
            $jadwal = JadwalKeberangkatan::with('kapal')->findOrFail($id);
            return response()->json($jadwal);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }
    }

    /**
     * 🔐 ADMIN - UPDATE
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'kapal_id' => 'sometimes|required|exists:kapals,id',
                'tanggal_berangkat' => 'sometimes|required|date',
                'jam_berangkat' => 'sometimes|required|date_format:H:i',
                'tujuan' => 'sometimes|required|string',
                'status' => 'sometimes|required|in:tersedia,penuh,batal',
            ]);

            $jadwal = JadwalKeberangkatan::findOrFail($id);
            $jadwal->update($data);

            return response()->json($jadwal);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal update jadwal'], 500);
        }
    }

    /**
     * 🔐 ADMIN - DELETE
     */
    public function destroy($id)
    {
        try {
            $jadwal = JadwalKeberangkatan::findOrFail($id);
            $jadwal->delete();
            return response()->json(['message' => 'Jadwal berhasil dihapus']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus jadwal'], 500);
        }
    }
}
