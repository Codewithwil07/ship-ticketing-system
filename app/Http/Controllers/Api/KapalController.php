<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kapal;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class KapalController extends Controller
{
   public function index(Request $request)
{
    try {
        $kapals = Kapal::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where('nama_kapal', 'like', "%{$request->search}%");
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // ✅ Pagination 10 per page

        return response()->json($kapals);

    } catch (Throwable $e) {
        return response()->json(['message' => 'Gagal memuat data kapal'], 500);
    }
}


    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nama_kapal' => 'required|string',
                'tipe' => 'required|string',
                'kapasitas' => 'required|integer',
                'kode_kapal' => 'required|string',
                'rute' => 'required|string',
                'home_base' => 'required|string',
                'status' => 'required|string',
                'operator' => 'required|string',
            ]);

            $kapal = Kapal::create($data);
            return response()->json($kapal, 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menyimpan kapal'], 500);
        }
    }

    public function show($id)
    {
        try {
            $kapal = Kapal::findOrFail($id);
            return response()->json($kapal, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Kapal tidak ditemukan'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal memuat data kapal'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $kapal = Kapal::findOrFail($id);

            $data = $request->validate([
                'nama_kapal' => 'required|string',
                'tipe' => 'required|string',
                'kapasitas' => 'required|integer',
                'kode_kapal' => 'required|string',
                'rute' => 'required|string',
                'home_base' => 'required|string',
                'status' => 'required|string',
                'operator' => 'required|string',
            ]);

            $kapal->update($data);
            return response()->json($kapal, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Kapal tidak ditemukan'], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal mengupdate kapal'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kapal = Kapal::findOrFail($id);
            $kapal->delete();

            return response()->json(['message' => 'Kapal berhasil dihapus'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Kapal tidak ditemukan'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus kapal'], 500);
        }
    }
}
