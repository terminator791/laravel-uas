<?php

namespace Database\Factories;

use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

class WargaFactory extends Factory
{
    protected $model = Warga::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'alamat' => 'Jl. '.fake()->streetName().' No. '.fake()->numberBetween(1, 100),
        ];
    }
}
