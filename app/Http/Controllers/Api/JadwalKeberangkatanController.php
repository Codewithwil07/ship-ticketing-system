<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalKeberangkatan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class JadwalKeberangkatanController extends Controller
{
    /**
     * 🔐 ADMIN - GET semua jadwal (search, filter, pagination)
     */
    public function index(Request $request)
    {
        try {
            $query = JadwalKeberangkatan::with('kapal');

            if ($search = $request->query('search')) {
                // PENCARIAN SEKARANG MENCAKUP 'asal' DAN 'tujuan'
                $query->where(function ($q) use ($search) {
                    $q->where('asal', 'like', "%$search%")
                        ->orWhere('tujuan', 'like', "%$search%")
                        ->orWhereHas('kapal', function ($subq) use ($search) {
                            $subq->where('nama_kapal', 'like', "%$search%");
                        });
                });
            }

            if ($status = $request->query('status')) {
                $query->where('status', $status);
            }

            $perPage = $request->query('per_page', 10);
            return response()->json($query->latest()->paginate($perPage));
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memuat data jadwal', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🔐 USER - GET semua jadwal tersedia tanpa pagination
     */
    public function getUser()
    {
        try {
            $data = JadwalKeberangkatan::with('kapal')
                ->where('status', 'tersedia')
                ->orderBy('tanggal_berangkat', 'asc')
                ->get();

            return response()->json($data);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memuat data jadwal', 'error' => $e->getMessage()], 500);
        }
    }


    public function showJadwal($id)
    {
        try {
            $jadwal = JadwalKeberangkatan::with('kapal')->findOrFail($id);
            return response()->json($jadwal);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memuat detail jadwal', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🔐 ADMIN - CREATE
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI DITAMBAHKAN UNTUK 'asal' DAN 'harga'
            $data = $request->validate([
                'kapal_id'          => 'required|exists:kapals,id',
                'tanggal_berangkat' => 'required|date',
                'jam_berangkat'     => 'required|date_format:H:i',
                'asal'              => 'required|string|max:255', // DITAMBAHKAN
                'tujuan'            => 'required|string|max:255',
                'status'            => 'required|in:tersedia,selesai,dibatalkan',
                'harga'             => 'required|integer|min:0', // DITAMBAHKAN
            ]);

            $jadwal = JadwalKeberangkatan::create($data);
            return response()->json($jadwal, 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menambah jadwal', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🔐 ADMIN - SHOW
     */
    public function show($id)
    {
        try {
            $jadwal = JadwalKeberangkatan::with('kapal')->findOrFail($id);
            return response()->json($jadwal);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memuat detail jadwal', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🔐 ADMIN - UPDATE
     */
    public function update(Request $request, $id)
    {
        try {
            // VALIDASI DITAMBAHKAN UNTUK 'asal' DAN 'harga'
            $data = $request->validate([
                'kapal_id'          => 'sometimes|required|exists:kapals,id',
                'tanggal_berangkat' => 'sometimes|required|date',
                'jam_berangkat'     => 'sometimes|required|date_format:H:i',
                'asal'              => 'sometimes|required|string|max:255', // DITAMBAHKAN
                'tujuan'            => 'sometimes|required|string|max:255',
                'status'            => 'sometimes|required|in:tersedia,selesai,dibatalkan',
                'harga'             => 'sometimes|required|integer|min:0', // DITAMBAHKAN
            ]);

            $jadwal = JadwalKeberangkatan::findOrFail($id);
            $jadwal->update($data);

            return response()->json($jadwal);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal update jadwal', 'error' => $e->getMessage()], 500);
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
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus jadwal', 'error' => $e->getMessage()], 500);
        }
    }
}
