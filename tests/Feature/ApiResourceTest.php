<?php

namespace Tests\Feature;

use App\Models\Chave;
use App\Models\Emprestimo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_requires_authentication_to_list_keys(): void
    {
        $this->getJson('/api/v1/keys')->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_read_update_and_delete_a_key(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/keys', ['name' => 'Chave 01', 'place' => 'Recepção'])
            ->assertCreated()->assertJsonPath('data.name', 'Chave 01');
        $key = Chave::query()->firstOrFail();

        $this->getJson("/api/v1/keys/{$key->id}")->assertOk()->assertJsonPath('data.place', 'Recepção');
        $this->putJson("/api/v1/keys/{$key->id}", ['name' => 'Chave principal', 'place' => 'Recepção'])
            ->assertOk()->assertJsonPath('data.name', 'Chave principal');
        $this->patchJson("/api/v1/keys/{$key->id}", ['place' => 'Bloco A'])
            ->assertOk()->assertJsonPath('data.place', 'Bloco A');
        $this->deleteJson("/api/v1/keys/{$key->id}")->assertNoContent();

        $this->assertModelMissing($key);
    }

    public function test_key_creation_rejects_missing_fields_and_duplicate_names(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Chave::factory()->create(['name' => 'Chave existente']);

        $this->postJson('/api/v1/keys', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'place']);
        $this->postJson('/api/v1/keys', ['name' => 'Chave existente', 'place' => 'Recepção'])
            ->assertUnprocessable()->assertJsonValidationErrors(['name']);
        $this->postJson('/api/v1/keys', ['name' => ['valor inválido'], 'place' => 'Recepção'])
            ->assertUnprocessable()->assertJsonValidationErrors(['name']);
    }

    public function test_authenticated_user_can_create_return_and_keep_loan_history(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $key = Chave::factory()->create();

        $this->postJson('/api/v1/loans', ['key_id' => $key->id, 'person' => 'Ana Silva'])
            ->assertCreated()->assertJsonPath('data.status', 'open');
        $loan = Emprestimo::query()->firstOrFail();

        $this->getJson('/api/v1/loans?status=open')->assertOk()->assertJsonPath('data.0.id', $loan->id);
        $this->postJson('/api/v1/loans', ['key_id' => $key->id, 'person' => 'Bruno Lima'])->assertConflict();
        $this->postJson("/api/v1/loans/{$loan->id}/return")->assertOk()->assertJsonPath('data.status', 'returned');
        $this->deleteJson("/api/v1/loans/{$loan->id}")->assertConflict();
        $this->deleteJson("/api/v1/keys/{$key->id}")->assertConflict();

        $this->assertModelExists($loan->refresh());
    }

    public function test_loan_creation_rejects_a_missing_key_reference(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/loans', ['key_id' => 9999, 'person' => 'Ana Silva'])
            ->assertUnprocessable()->assertJsonValidationErrors(['key_id']);
    }

    public function test_api_returns_not_found_for_an_unknown_key(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/v1/keys/9999')->assertNotFound();
    }

    public function test_loan_can_be_read_and_updated_before_return(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $loan = Emprestimo::factory()->create();

        $this->getJson("/api/v1/loans/{$loan->id}")->assertOk()->assertJsonPath('data.person', $loan->person);
        $this->patchJson("/api/v1/loans/{$loan->id}", ['person' => 'Nome atualizado'])
            ->assertOk()->assertJsonPath('data.person', 'Nome atualizado');
    }

    public function test_token_endpoint_issues_a_bearer_token_for_valid_credentials(): void
    {
        $user = User::factory()->create(['email' => 'api@example.test', 'password' => 'secure-password']);

        $tokenResponse = $this->postJson('/api/v1/token', ['email' => $user->email, 'password' => 'secure-password', 'device_name' => 'Thunder Client'])
            ->assertCreated()->assertJsonPath('data.token_type', 'Bearer')->assertJsonStructure(['data' => ['access_token']]);

        $this->withToken($tokenResponse->json('data.access_token'))->deleteJson('/api/v1/token')->assertNoContent();
    }

    public function test_active_loan_can_be_cancelled_without_deleting_its_key(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $loan = Emprestimo::factory()->create();
        $key = $loan->chave;

        $this->deleteJson("/api/v1/loans/{$loan->id}")->assertNoContent();

        $this->assertModelMissing($loan);
        $this->assertModelExists($key);
    }

    public function test_portuguese_chaves_api_route_creates_a_key_without_web_csrf(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/chaves', [
            'name' => 'Chave Sala 101',
            'place' => 'Sala 101',
        ])->assertCreated()->assertJsonPath('data.name', 'Chave Sala 101');
    }

    public function test_unversioned_portuguese_api_route_accepts_the_postman_key_fields(): void
    {
        $this->postJson('/api/chaves', [
            'nome' => 'Chave Sala 102',
            'descricao' => 'Sala 102',
            'status' => 'disponivel',
        ])->assertUnauthorized();

        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/chaves', [
            'nome' => 'Chave Sala 102',
            'descricao' => 'Sala 102',
            'status' => 'ocupada',
        ])->assertUnprocessable()->assertJsonValidationErrors(['status']);

        $this->postJson('/api/chaves', [
            'nome' => 'Chave Sala 102',
            'descricao' => 'Sala 102',
            'status' => 'disponivel',
        ])->assertCreated()
            ->assertJsonPath('data.name', 'Chave Sala 102')
            ->assertJsonPath('data.place', 'Sala 102')
            ->assertJsonPath('data.status', 'Disponível');

        $this->assertDatabaseHas('keys', [
            'name' => 'Chave Sala 102',
            'place' => 'Sala 102',
            'status' => 'Disponível',
        ]);
    }
}
