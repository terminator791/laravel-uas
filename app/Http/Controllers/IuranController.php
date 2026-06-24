<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\Warga;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index()
    {
        $iurans = Iuran::with('warga')->latest()->paginate(10);
        $wargas = Warga::all();
        $stats = [
            'total_warga' => Warga::count(),
            'total_iuran' => Iuran::count(),
            'total_tunggakan' => Iuran::where('status', 'pending')->sum('jumlah_iuran'),
            'iuran_pending' => Iuran::where('status', 'pending')->count(),
        ];

        return view('iuran.index', compact('iurans', 'wargas', 'stats'));
    }

    public function tunggakan(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');

        $wargas = Warga::with(['iurans' => function ($query) use ($tahun) {
            $query->whereYear('bulan', $tahun)
                ->where('status', 'pending');
        }])->get();

        $data = $wargas->map(function ($warga) {
            $pendingIurans = $warga->iurans->where('status', 'pending');

            return [
                'warga' => $warga,
                'total_tunggakan' => $pendingIurans->sum('jumlah_iuran'),
                'detail_tunggakan' => $pendingIurans->values(),
            ];
        })->filter(function ($item) {
            return $item['total_tunggakan'] > 0;
        })->values();

        $tahunList = Iuran::selectRaw('EXTRACT(YEAR FROM bulan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('iuran.tunggakan', compact('data', 'tahun', 'tahunList'));
    }
}
