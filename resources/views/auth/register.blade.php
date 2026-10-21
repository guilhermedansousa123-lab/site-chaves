@extends('layouts.app')

@section('title', 'Criar conta')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 65vh">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">
        <div class="card p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="brand-mark"><i class="bi bi-person-plus-fill"></i></span>
                <h1 class="h4 fw-bold mt-3 mb-1">Criar sua conta</h1>
                <p class="page-subtitle mb-0">Preencha seus dados para começar.</p>
            </div>
            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <label class="form-label" for="name">Nome</label>
                <input class="form-control mb-3 @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>
                @error('name') <div class="invalid-feedback d-block mb-3">{{ $message }}</div> @enderror
                <label class="form-label" for="email">E-mail</label>
                <input class="form-control mb-3 @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
                @error('email') <div class="invalid-feedback d-block mb-3">{{ $message }}</div> @enderror
                <label class="form-label" for="password">Senha</label>
                <input class="form-control mb-3 @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                @error('password') <div class="invalid-feedback d-block mb-3">{{ $message }}</div> @enderror
                <label class="form-label" for="password_confirmation">Confirmar senha</label>
                <input class="form-control mb-4" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                <button class="btn btn-primary w-100" type="submit">Criar conta</button>
            </form>
            <p class="text-center small mt-4 mb-0">Já tem uma conta? <a href="{{ route('login') }}">Entrar</a></p>
        </div>
    </div>
</div>
@endsection
