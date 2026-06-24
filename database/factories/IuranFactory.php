<?php

namespace Database\Factories;

use App\Models\Iuran;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

class IuranFactory extends Factory
{
    protected $model = Iuran::class;

    public function definition(): array
    {
        return [
            'id_warga' => Warga::factory(),
            'bulan' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'jumlah_iuran' => fake()->randomElement([25000, 50000, 75000, 100000]),
            'status' => fake()->randomElement(['pending', 'selesai']),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'pending']);
    }

    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'selesai']);
    }
}
