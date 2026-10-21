<?php

namespace Tests\Feature;

use App\Models\Chave;
use App\Models\Emprestimo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeyLoanWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_and_user_can_open_dashboard(): void
    {
        $this->get('/')->assertRedirectToRoute('login');
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertOk()
            ->assertSee('Painel de emprÃ©stimos e devoluÃ§Ãµes.')
            ->assertDontSee('Total de chaves')
            ->assertDontSee('DisponÃ­veis');
    }

    public function test_key_management_pages_are_not_available_on_the_website(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/keys')->assertNotFound();
        $this->get('/chaves')->assertNotFound();
        $this->post('/keys', ['name' => 'Chave', 'place' => 'RecepÃ§Ã£o'])->assertNotFound();
        $this->post('/chaves', ['name' => 'Chave', 'place' => 'RecepÃ§Ã£o'])->assertNotFound();

        $this->assertDatabaseCount('keys', 0);
    }

    public function test_user_can_register_and_return_a_loan(): void
    {
        $this->actingAs(User::factory()->create());
        $key = Chave::factory()->create(['name' => 'Chave de teste', 'place' => 'Sala 2']);

        $this->get(route('loans.create'))->assertOk()->assertSee('Registrar emprÃ©stimo');
        $this->post(route('loans.store'), [
            'key_id' => $key->id,
            'person' => 'JoÃ£o Teste',
            'note' => 'Visita',
        ])->assertRedirectToRoute('loans.index');

        $loan = Emprestimo::query()->firstOrFail();
        $this->get(route('loans.index'))->assertOk()->assertSee('JoÃ£o Teste');
        $this->patch(route('loans.return', $loan))->assertRedirect();

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'returned_at' => $loan->fresh()->returned_at,
        ]);
    }
}
