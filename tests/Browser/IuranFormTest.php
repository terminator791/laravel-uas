<?php

namespace Tests\Browser;

use App\Models\Iuran;
use App\Models\Warga;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IuranFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_open_add_iuran_form(): void
    {
        $warga = Warga::factory()->create([
            'nama' => 'John Doe',
            'alamat' => 'Jl. Mawar No. 10',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/iuran')
                ->waitForText('Daftar Iuran')
                ->click('button[class*="bg-blue"]')
                ->waitForText('Tambah Iuran Baru')
                ->assertSee('Nama Warga')
                ->assertSee('Bulan')
                ->assertSee('Jumlah Iuran')
                ->assertSee('Status');
        });
    }

    public function test_user_can_view_tunggakan_page(): void
    {
        $warga = Warga::factory()->create([
            'nama' => 'John Doe',
            'alamat' => 'Jl. Mawar No. 10',
        ]);

        Iuran::factory()->create([
            'id_warga' => $warga->id,
            'bulan' => date('Y').'-01-01',
            'jumlah_iuran' => 50000,
            'status' => 'pending',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/iuran/tunggakan')
                ->waitForText('Laporan Tunggakan')
                ->assertSee('John Doe')
                ->assertSee('50.000');
        });
    }

    public function test_api_endpoint_returns_iuran_data(): void
    {
        $response = $this->get('/api/iuran');
        $response->assertStatus(200);
    }
}
