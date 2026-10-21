<?php

namespace Database\Factories;

use App\Models\Chave;
use App\Models\Emprestimo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Emprestimo>
 */
class EmprestimoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key_id' => Chave::factory(),
            'person' => fake()->name(),
            'note' => fake()->optional()->sentence(5),
            'loaned_at' => now(),
            'returned_at' => null,
        ];
    }
}
