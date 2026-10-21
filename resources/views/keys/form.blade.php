@extends('layouts.app')

@section('title', $key->exists ? 'Editar chave' : 'Nova chave')

@section('content')
<div class="mb-4"><h1 class="page-title mb-1">{{ $key->exists ? 'Editar chave' : 'Cadastrar chave' }}</h1><p class="page-subtitle mb-0">Informe o nome e o local para identificar a chave.</p></div>
<div class="row justify-content-center"><div class="col-12 col-lg-7 col-xl-6"><div class="card p-4 p-md-5">
    <form method="POST" action="{{ $key->exists ? route('keys.update', $key) : route('keys.store') }}">
        @csrf
        @if ($key->exists) @method('PUT') @endif
        <label class="form-label" for="name">Nome da chave</label>
        <input class="form-control mb-1 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $key->name) }}" placeholder="Ex.: 023 — Sala de reunião" maxlength="80" required>
        @error('name') <div class="invalid-feedback d-block mb-3">{{ $message }}</div> @enderror
        <label class="form-label mt-3" for="place">Local</label>
        <input class="form-control mb-1 @error('place') is-invalid @enderror" id="place" name="place" value="{{ old('place', $key->place) }}" placeholder="Ex.: Bloco A" maxlength="80" required>
        @error('place') <div class="invalid-feedback d-block mb-3">{{ $message }}</div> @enderror
        <div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-light" href="{{ route('keys.index') }}">Cancelar</a><button class="btn btn-primary" type="submit">{{ $key->exists ? 'Salvar alterações' : 'Cadastrar chave' }}</button></div>
    </form>
</div></div></div>
@endsection
