<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = Pemesanan::with(['user', 'jadwal'])
                ->when($request->search, function ($query) use ($request) {
                    $query->where('nama_penumpang', 'like', '%' . $request->search . '%');
                })
                ->when($request->status, function ($query) use ($request) {
                    $query->where('status', $request->status);
                })
                ->latest()
                ->paginate(10);

            return response()->json($data);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memuat data pemesanan'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'jadwal_id' => 'required|exists:jadwal_keberangkatans,id',
                'jumlah_tiket' => 'required|integer|min:1',
                'total_harga' => 'required|integer',
                'status' => 'required|in:pending,diterima,ditolak',
            ]);

            $user = $request->user();

            $pemesanan = Pemesanan::create([
                'user_id' => $user->id,
                'nama_penumpang' => $user->name, // ✅ auto ambil dari user login
                'jadwal_id' => $data['jadwal_id'],
                'jumlah_tiket' => $data['jumlah_tiket'],
                'total_harga' => $data['total_harga'],
                'status' => $data['status'],
            ]);

            return response()->json($pemesanan, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json(
                [
                    'message' => 'Gagal menyimpan pemesanan'
                ],
                500
            );
        }
    }

    public function getUser(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $data = Pemesanan::with('jadwal')
                ->where('user_id', $userId)
                ->latest()
                ->get(); // ✅ no pagination

            return response()->json($data);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengambil data pemesanan user',
            ], 500);
        }
    }




    public function show($id)
    {
        try {
            $data = Pemesanan::with(['user', 'jadwal'])->findOrFail($id);
            return response()->json($data);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memuat data'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pemesanan = Pemesanan::findOrFail($id);

            $data = $request->validate([
                'status' => 'required|in:pending,lunas,bayar', // required untuk admin
            ]);

            $pemesanan->update($data);
            return response()->json([
                'message' => 'Status pemesanan berhasil diperbarui',
                'data' => $pemesanan
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal update data'], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $pemesanan = Pemesanan::findOrFail($id);
            $pemesanan->delete();

            return response()->json(['message' => 'Pemesanan berhasil dihapus']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus data'], 500);
        }
    }
}
