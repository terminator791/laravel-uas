<?php

namespace Tests\Feature;

use App\Models\Iuran;
use App\Models\Warga;
use Tests\TestCase;

class IuranAddTest extends TestCase
{
    public function test_can_create_and_update_iuran_with_one_warga(): void
    {
        // Create 1 warga
        $warga = Warga::factory()->create();

        // Test 1: Create iuran with pending status
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
            'bulan' => '2024-04-01',
            'jumlah_iuran' => 50000,
            'status' => 'pending',
        ]);

        // Get the iuran that was just created
        $iuran = Iuran::where('id_warga', $warga->id)->first();

        // Test 2: Update same iuran from pending to selesai
        $updateResponse = $this->putJson("/api/iuran/{$iuran->id}", [
            'status' => 'selesai',
        ]);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Data iuran berhasil diperbarui',
            ])
            ->assertJsonPath('data.id', $iuran->id)
            ->assertJsonPath('data.status', 'selesai');

        $this->assertDatabaseHas('iuran', [
            'id' => $iuran->id,
            'status' => 'selesai',
        ]);
    }
}
