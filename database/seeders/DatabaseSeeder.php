<?php

namespace Database\Seeders;

use App\Models\Chave;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(FuncionarioSeeder::class);
        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password')],
        );

        $keys = collect([
            ['name' => 'Chave principal', 'place' => 'Recepção'],
            ['name' => 'Chave da sala de reunião', 'place' => 'Sala de reunião'],
            ['name' => 'Chave do laboratório', 'place' => 'Laboratório'],
            ['name' => 'Chave do almoxarifado', 'place' => 'Almoxarifado'],
            ['name' => 'Chave do portão lateral', 'place' => 'Portão lateral'],
            ['name' => 'Chave da sala de TI', 'place' => 'Sala de TI'],
            ['name' => 'Chave do arquivo', 'place' => 'Arquivo'],
            ['name' => 'Chave da garagem', 'place' => 'Garagem'],
            ['name' => 'Chave da administração', 'place' => 'Administração'],
            ['name' => 'Chave do auditório', 'place' => 'Auditório'],
            ['name' => 'Chave da sala de treinamento', 'place' => 'Treinamento'],
            ['name' => 'Chave do estacionamento', 'place' => 'Estacionamento'],
            ['name' => 'Chave da entrada de serviço', 'place' => 'Entrada de serviço'],
            ['name' => 'Chave do estoque reserva', 'place' => 'Estoque'],
        ])->mapWithKeys(fn (array $data): array => [
            $data['name'] => Chave::query()->firstOrCreate(
                ['name' => $data['name']],
                ['place' => $data['place']],
            ),
        ]);

        $loans = [
            [
                'key' => 'Chave principal',
                'person' => 'Ana Souza',
                'note' => 'Atendimento de visitante',
                'loaned_at' => '2026-10-17 08:00:00',
                'returned_at' => '2026-10-17 10:30:00',
            ],
            [
                'key' => 'Chave da sala de reunião',
                'person' => 'Carlos Lima',
                'note' => 'Reunião com equipe',
                'loaned_at' => '2026-10-16 13:00:00',
                'returned_at' => '2026-10-16 14:00:00',
            ],
            [
                'key' => 'Chave do almoxarifado',
                'person' => 'Júlia Costa',
                'note' => 'Retirada de materiais',
                'loaned_at' => '2026-10-15 09:00:00',
                'returned_at' => '2026-10-15 10:00:00',
            ],
            [
                'key' => 'Chave do laboratório',
                'person' => 'Pedro Santos',
                'note' => 'Aula prática',
                'loaned_at' => '2026-10-19 09:00:00',
                'returned_at' => null,
            ],
            [
                'key' => 'Chave da garagem',
                'person' => 'Marina Oliveira',
                'note' => 'Acesso para entrega',
                'loaned_at' => '2026-10-19 10:00:00',
                'returned_at' => null,
            ],
            [
                'key' => 'Chave da administração',
                'person' => 'Ana Paula',
                'note' => 'Organização de documentos',
                'loaned_at' => '2026-10-14 08:30:00',
                'returned_at' => '2026-10-14 09:15:00',
            ],
            [
                'key' => 'Chave do auditório',
                'person' => 'Bruno Reis',
                'note' => 'Preparação de evento',
                'loaned_at' => '2026-10-15 13:00:00',
                'returned_at' => '2026-10-15 16:00:00',
            ],
            [
                'key' => 'Chave da sala de treinamento',
                'person' => 'Fernanda Melo',
                'note' => 'Treinamento da equipe',
                'loaned_at' => '2026-10-18 09:00:00',
                'returned_at' => '2026-10-18 11:00:00',
            ],
            [
                'key' => 'Chave do estacionamento',
                'person' => 'Rafael Nunes',
                'note' => 'Acesso para manutenção',
                'loaned_at' => '2026-10-19 11:00:00',
                'returned_at' => null,
            ],
            [
                'key' => 'Chave da entrada de serviço',
                'person' => 'Beatriz Rocha',
                'note' => 'Recebimento de materiais',
                'loaned_at' => '2026-10-19 12:00:00',
                'returned_at' => null,
            ],        ];

        foreach ($loans as $loan) {
            $key = $keys->get($loan['key']);
            $existingLoan = $key->emprestimos()
                ->where('person', $loan['person'])
                ->where('loaned_at', $loan['loaned_at'])
                ->exists();

            if ($existingLoan || ($loan['returned_at'] === null && $key->emprestimos()->whereNull('returned_at')->exists())) {
                continue;
            }

            $key->emprestimos()->create([
                'person' => $loan['person'],
                'note' => $loan['note'],
                'loaned_at' => $loan['loaned_at'],
                'returned_at' => $loan['returned_at'],
            ]);
        }
    }
}
