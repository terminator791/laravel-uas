<?php

namespace Database\Seeders;

use App\Models\Warga;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $wargas = [
            ['nama' => 'John Doe', 'alamat' => 'Jl. Mawar No. 10'],
            ['nama' => 'Jane Smith', 'alamat' => 'Jl. Melati No. 5'],
            ['nama' => 'Ahmad Fauzi', 'alamat' => 'Jl. Anggrek No. 15'],
            ['nama' => 'Siti Aminah', 'alamat' => 'Jl. Dahlia No. 8'],
            ['nama' => 'Budi Santoso', 'alamat' => 'Jl. Kenanga No. 22'],
        ];

        foreach ($wargas as $warga) {
            Warga::create($warga);
        }
    }
}
