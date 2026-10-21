<?php

namespace Database\Factories;

use App\Models\Funcionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Funcionario>
 */
class FuncionarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'cargo' => fake()->jobTitle(),
        ];
    }
}
