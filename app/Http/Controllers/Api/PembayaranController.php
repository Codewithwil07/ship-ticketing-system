<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;


class PembayaranController extends Controller

{

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'pemesanan_id' => 'required|exists:pemesanans,id',
                'metode_pembayaran' => 'required|string|max:50',
                'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $pemesanan = $request->user()->pemesanans()->findOrFail($data['pemesanan_id']);

            $path = $request->file('bukti')->store('bukti-pembayaran', 'public');

            $pembayaran = Pembayaran::create([
                'pemesanan_id' => $data['pemesanan_id'],
                'metode_pembayaran' => $data['metode_pembayaran'],
                'bukti' => $path,
                'status_verifikasi' => 'menunggu',
            ]);

            return response()->json($pembayaran, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal upload bukti',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUser(Request $request)
    {
        try {
            $user = $request->user();

            $riwayat = $user->pemesanans()
                ->with('pembayaran')
                ->whereHas('pembayaran') // hanya yang sudah upload bukti
                ->get()
                ->map(function ($pemesanan) {
                    return [
                        'id' => $pemesanan->pembayaran->id ?? null,
                        'jadwal_id' => $pemesanan->jadwal_id,
                        'jumlah_tiket' => $pemesanan->jumlah_tiket,
                        'total_harga' => $pemesanan->total_harga,
                        'status_pemesanan' => $pemesanan->status,
                        'metode_pembayaran' => $pemesanan->pembayaran->metode_pembayaran ?? null,
                        'status_verifikasi' => $pemesanan->pembayaran->status_verifikasi ?? null,
                        'bukti' => $pemesanan->pembayaran->bukti ?? null,
                        'tanggal' => $pemesanan->created_at->toDateString()
                    ];
                });

            return response()->json($riwayat);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengambil riwayat pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * 🔍 List semua pembayaran (search, filter, pagination)
     */
    public function index(Request $request)
    {
        try {
            $query = Pembayaran::with(['pemesanan.user']);

            if ($search = $request->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('metode_pembayaran', 'like', "%$search%")
                        ->orWhereHas('pemesanan.user', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%$search%");
                        });
                });
            }

            if ($status = $request->query('status')) {
                $query->where('status_verifikasi', $status);
            }

            $perPage = $request->query('per_page', 10);
            return response()->json($query->latest()->paginate($perPage));
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memuat data pembayaran', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 📄 Lihat detail 1 pembayaran
     */
    public function show($id)
    {
        try {
            $pembayaran = Pembayaran::with(['pemesanan.user'])->findOrFail($id);
            return response()->json($pembayaran);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * ✅ Verifikasi status pembayaran (admin)
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'status_verifikasi' => 'required|in:menunggu,diterima,ditolak'
            ]);

            $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);
            $pembayaran->update($data);

            // Jika diterima, update status pemesanan juga
            if ($data['status_verifikasi'] === 'diterima') {
                $pembayaran->pemesanan->update(['status' => 'lunas']);
            }

            return response()->json(['message' => 'Status pembayaran diperbarui', 'data' => $pembayaran]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memperbarui status', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * ❌ Hapus pembayaran & bukti file
     */
    public function destroy($id)
    {
        try {
            $pembayaran = Pembayaran::findOrFail($id);

            if ($pembayaran->bukti && Storage::disk('public')->exists($pembayaran->bukti)) {
                Storage::disk('public')->delete($pembayaran->bukti);
            }

            $pembayaran->delete();

            return response()->json(['message' => 'Pembayaran berhasil dihapus']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus pembayaran', 'error' => $e->getMessage()], 500);
        }
    }
}
