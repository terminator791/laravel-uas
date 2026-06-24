<?php

namespace Tests\Feature;

use App\Models\Iuran;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IuranApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_iuran(): void
    {
        $warga = Warga::factory()->create();

        $payload = [
            'id_warga' => $warga->id,
            'bulan' => '2024-04',
            'jumlah_iuran' => 50000,
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/iuran', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Data iuran berhasil ditambahkan',
            ]);

        $this->assertDatabaseHas('iuran', [
            'id_warga' => $warga->id,
            'jumlah_iuran' => 50000,
            'status' => 'pending',
        ]);
    }

    public function test_can_get_all_iuran(): void
    {
        $warga = Warga::factory()->create();
        Iuran::factory()->count(3)->create(['id_warga' => $warga->id]);

        $response = $this->getJson('/api/iuran');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'id_warga',
                        'bulan',
                        'jumlah_iuran',
                        'status',
                    ],
                ],
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_get_iuran_by_id(): void
    {
        $warga = Warga::factory()->create();
        $iuran = Iuran::factory()->create(['id_warga' => $warga->id]);

        $response = $this->getJson("/api/iuran/{$iuran->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'id_warga',
                    'bulan',
                    'jumlah_iuran',
                    'status',
                ],
            ]);
    }

    public function test_can_update_iuran(): void
    {
        $warga = Warga::factory()->create();
        $iuran = Iuran::factory()->create([
            'id_warga' => $warga->id,
            'status' => 'pending',
        ]);

        $payload = [
            'status' => 'selesai',
        ];

        $response = $this->putJson("/api/iuran/{$iuran->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data iuran berhasil diperbarui',
            ]);

        $this->assertDatabaseHas('iuran', [
            'id' => $iuran->id,
            'status' => 'selesai',
        ]);
    }

    public function test_can_delete_iuran(): void
    {
        $warga = Warga::factory()->create();
        $iuran = Iuran::factory()->create(['id_warga' => $warga->id]);

        $response = $this->deleteJson("/api/iuran/{$iuran->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data iuran berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('iuran', [
            'id' => $iuran->id,
        ]);
    }

    public function test_can_get_tunggakan_by_year(): void
    {
        $warga1 = Warga::factory()->create(['nama' => 'John Doe', 'alamat' => 'Jl. Mawar No. 10']);
        $warga2 = Warga::factory()->create(['nama' => 'Jane Smith', 'alamat' => 'Jl. Melati No. 5']);

        Iuran::factory()->create(['id_warga' => $warga1->id, 'bulan' => '2024-01-01', 'jumlah_iuran' => 50000, 'status' => 'pending']);
        Iuran::factory()->create(['id_warga' => $warga1->id, 'bulan' => '2024-02-01', 'jumlah_iuran' => 50000, 'status' => 'pending']);
        Iuran::factory()->create(['id_warga' => $warga2->id, 'bulan' => '2024-01-01', 'jumlah_iuran' => 50000, 'status' => 'selesai']);

        $response = $this->getJson('/api/iuran/tunggakan/2024');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id_warga',
                        'nama',
                        'alamat',
                        'total_tunggakan',
                        'detail_tunggakan',
                    ],
                ],
            ]);

        $this->assertEquals(1, count($response->json('data')));
        $this->assertEquals('John Doe', $response->json('data.0.nama'));
        $this->assertEquals(100000, $response->json('data.0.total_tunggakan'));
        $this->assertEquals(2, count($response->json('data.0.detail_tunggakan')));
    }

    public function test_create_iuran_validation(): void
    {
        $response = $this->postJson('/api/iuran', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_warga', 'bulan', 'jumlah_iuran', 'status']);
    }

    public function test_update_nonexistent_iuran(): void
    {
        $response = $this->putJson('/api/iuran/999', ['status' => 'selesai']);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data iuran tidak ditemukan',
            ]);
    }

    public function test_delete_nonexistent_iuran(): void
    {
        $response = $this->deleteJson('/api/iuran/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data iuran tidak ditemukan',
            ]);
    }
}
