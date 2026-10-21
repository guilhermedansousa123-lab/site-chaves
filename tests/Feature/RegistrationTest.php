<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_registration_form(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Criar sua conta')
            ->assertSee('Já tem uma conta?');
    }

    public function test_user_can_register_and_is_logged_in(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Maria Teste',
            'email' => 'maria@example.com',
            'password' => 'password-safe',
            'password_confirmation' => 'password-safe',
        ])->assertRedirectToRoute('dashboard');

        $user = User::query()->where('email', 'maria@example.com')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame('Maria Teste', $user->name);
        $this->assertTrue(Hash::check('password-safe', $user->password));
    }

    public function test_registration_rejects_duplicate_email_and_mismatched_password(): void
    {
        User::factory()->create(['email' => 'maria@example.com']);

        $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Maria Teste',
            'email' => 'maria@example.com',
            'password' => 'password-safe',
            'password_confirmation' => 'another-password',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->assertDatabaseCount('users', 1);
    }
}
