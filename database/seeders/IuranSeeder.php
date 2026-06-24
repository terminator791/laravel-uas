<?php

namespace Database\Seeders;

use App\Models\Iuran;
use Illuminate\Database\Seeder;

class IuranSeeder extends Seeder
{
    public function run(): void
    {
        $iurans = [
            ['id_warga' => 1, 'bulan' => '2024-01-01', 'jumlah_iuran' => 50000, 'status' => 'pending'],
            ['id_warga' => 1, 'bulan' => '2024-02-01', 'jumlah_iuran' => 50000, 'status' => 'pending'],
            ['id_warga' => 1, 'bulan' => '2024-03-01', 'jumlah_iuran' => 50000, 'status' => 'pending'],
            ['id_warga' => 1, 'bulan' => '2024-04-01', 'jumlah_iuran' => 50000, 'status' => 'selesai'],
            ['id_warga' => 2, 'bulan' => '2024-01-01', 'jumlah_iuran' => 50000, 'status' => 'selesai'],
            ['id_warga' => 2, 'bulan' => '2024-02-01', 'jumlah_iuran' => 50000, 'status' => 'selesai'],
            ['id_warga' => 3, 'bulan' => '2024-01-01', 'jumlah_iuran' => 50000, 'status' => 'pending'],
            ['id_warga' => 3, 'bulan' => '2024-02-01', 'jumlah_iuran' => 50000, 'status' => 'pending'],
            ['id_warga' => 4, 'bulan' => '2024-01-01', 'jumlah_iuran' => 50000, 'status' => 'selesai'],
        ];

        foreach ($iurans as $iuran) {
            Iuran::create($iuran);
        }
    }
}
