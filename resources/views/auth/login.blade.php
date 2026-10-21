@extends('layouts.app')

@section('title', 'Entrar')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 65vh">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">
        <div class="card p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="brand-mark"><i class="bi bi-key-fill"></i></span>
                <h1 class="h4 fw-bold mt-3 mb-1">Acesso ao sistema</h1>
                <p class="page-subtitle mb-0">Entre com sua conta cadastrada.</p>
            </div>
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <label class="form-label" for="email">E-mail</label>
                <input class="form-control mb-3 @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email') <div class="invalid-feedback d-block mb-3">{{ $message }}</div> @enderror
                <label class="form-label" for="password">Senha</label>
                <input class="form-control mb-2 @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="current-password" required>
                @error('password') <div class="invalid-feedback d-block mb-3">{{ $message }}</div> @enderror
                <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label small" for="remember">Manter conectado</label></div>
                <button class="btn btn-primary w-100" type="submit">Entrar</button>
            </form>
            <p class="text-center small mt-4 mb-0">Ainda não tem uma conta? <a href="{{ route('register') }}">Cadastre-se</a></p>
        </div>
    </div>
</div>
@endsection
