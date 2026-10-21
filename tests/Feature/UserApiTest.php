<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_can_register_a_user_without_exposing_the_password(): void
    {
        $response = $this->postJson('/api/v1/users', [
            'name' => 'Ana Silva',
            'email' => 'ana@example.com',
            'password' => 'password-safe',
            'tipo' => 'admin',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Ana Silva')
            ->assertJsonMissingPath('data.password');

        $user = User::query()->where('email', 'ana@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('password-safe', $user->password));
        $response->assertJsonMissingPath('data.tipo');
    }

    public function test_api_registration_validates_required_fields_and_unique_email(): void
    {
        User::factory()->create(['email' => 'ana@example.com']);

        $this->postJson('/api/v1/users', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'password']);
        $this->postJson('/api/v1/users', [
            'name' => 'Ana Silva',
            'email' => 'ana@example.com',
            'password' => 'password-safe',
        ])->assertUnprocessable()->assertJsonValidationErrors(['email']);
    }

    public function test_api_user_profile_requires_authentication_and_only_returns_safe_fields(): void
    {
        $this->getJson('/api/v1/user')->assertUnauthorized();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/user')->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonMissingPath('data.password');
    }

    public function test_authenticated_user_can_update_only_their_own_profile(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        $this->patchJson('/api/v1/user', [
            'name' => 'Nome atualizado',
            'email' => $otherUser->email,
        ])->assertUnprocessable()->assertJsonValidationErrors(['email']);

        $this->patchJson('/api/v1/user', ['name' => 'Nome atualizado'])
            ->assertOk()->assertJsonPath('data.name', 'Nome atualizado');

        $this->assertSame('Nome atualizado', $user->fresh()->name);
    }

    public function test_authenticated_user_can_delete_their_account_and_tokens(): void
    {
        $user = User::factory()->create();
        $user->createToken('test token');
        Sanctum::actingAs($user);

        $this->deleteJson('/api/v1/user')->assertNoContent();

        $this->assertModelMissing($user);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
