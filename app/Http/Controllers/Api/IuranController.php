<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIuranRequest;
use App\Http\Requests\UpdateIuranRequest;
use App\Models\Iuran;
use App\Models\Warga;
use Illuminate\Http\JsonResponse;

class IuranController extends Controller
{
    public function index(): JsonResponse
    {
        $iurans = Iuran::with('warga')->get();

        return response()->json([
            'data' => $iurans,
        ]);
    }

    public function store(StoreIuranRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['bulan'] = $validated['bulan'].'-01';

        $iuran = Iuran::create($validated);

        return response()->json([
            'message' => 'Data iuran berhasil ditambahkan',
            'data' => $iuran,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $iuran = Iuran::with('warga')->find($id);

        if (! $iuran) {
            return response()->json([
                'message' => 'Data iuran tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'data' => $iuran,
        ]);
    }

    public function update(UpdateIuranRequest $request, int $id): JsonResponse
    {
        $iuran = Iuran::find($id);

        if (! $iuran) {
            return response()->json([
                'message' => 'Data iuran tidak ditemukan',
            ], 404);
        }

        $validated = $request->validated();

        if (isset($validated['bulan'])) {
            $validated['bulan'] = $validated['bulan'].'-01';
        }

        $iuran->update($validated);

        return response()->json([
            'message' => 'Data iuran berhasil diperbarui',
            'data' => $iuran,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $iuran = Iuran::find($id);

        if (! $iuran) {
            return response()->json([
                'message' => 'Data iuran tidak ditemukan',
            ], 404);
        }

        $iuran->delete();

        return response()->json([
            'message' => 'Data iuran berhasil dihapus',
        ]);
    }

    public function tunggakan(int $tahun): JsonResponse
    {
        $wargas = Warga::with(['iurans' => function ($query) use ($tahun) {
            $query->whereYear('bulan', $tahun)
                ->where('status', 'pending');
        }])->get();

        $data = $wargas->map(function ($warga) {
            $pendingIurans = $warga->iurans->where('status', 'pending');

            return [
                'id_warga' => $warga->id,
                'nama' => $warga->nama,
                'alamat' => $warga->alamat,
                'total_tunggakan' => $pendingIurans->sum('jumlah_iuran'),
                'detail_tunggakan' => $pendingIurans->map(function ($iuran) {
                    return [
                        'bulan' => $iuran->bulan->format('Y-m-d'),
                        'jumlah_iuran' => $iuran->jumlah_iuran,
                        'status' => $iuran->status,
                    ];
                })->values(),
            ];
        })->filter(function ($item) {
            return $item['total_tunggakan'] > 0;
        })->values();

        return response()->json([
            'data' => $data,
        ]);
    }
}
