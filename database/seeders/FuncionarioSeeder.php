<?php

namespace Database\Seeders;

use App\Models\Funcionario;
use Illuminate\Database\Seeder;

class FuncionarioSeeder extends Seeder
{
    public function run(): void
    {
        Funcionario::query()->firstOrCreate(
            ['email' => 'ana.souza@example.com'],
            ['nome' => 'Ana Souza', 'cargo' => 'Atendente'],
        );
    }
}
