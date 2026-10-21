@extends('layouts.app')

@section('title', 'Registrar empréstimo')

@section('content')
<div class="mb-4"><h1 class="page-title mb-1">Registrar empréstimo</h1><p class="page-subtitle mb-0">Informe a chave e a pessoa responsável.</p></div>
<div class="row justify-content-center"><div class="col-12 col-lg-7 col-xl-6"><div class="card p-4 p-md-5">
    <form method="POST" action="{{ route('loans.store') }}">
        @csrf
        <label class="form-label" for="key_id">Chave</label>
        <select class="form-select @error('key_id') is-invalid @enderror" id="key_id" name="key_id" required>
            <option value="">Selecione uma chave disponível</option>
            @foreach ($keys as $key) <option value="{{ $key->id }}" @selected(old('key_id') == $key->id)>{{ $key->name }} — {{ $key->place }}</option> @endforeach
        </select>
        @error('key_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        @if ($keys->isEmpty()) <p class="form-text">Não há chaves disponíveis para empréstimo.</p> @endif
        <label class="form-label mt-3" for="person">Responsável</label>
        <input class="form-control @error('person') is-invalid @enderror" id="person" name="person" value="{{ old('person') }}" placeholder="Nome do responsável" maxlength="80" required>
        @error('person') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        <label class="form-label mt-3" for="note">Observação <span class="text-secondary fw-normal">(opcional)</span></label>
        <input class="form-control @error('note') is-invalid @enderror" id="note" name="note" value="{{ old('note') }}" placeholder="Ex.: reunião na sala 2" maxlength="120">
        @error('note') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        <div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-light" href="{{ route('loans.index') }}">Cancelar</a><button class="btn btn-primary" type="submit" @disabled($keys->isEmpty())>Registrar empréstimo</button></div>
    </form>
</div></div></div>
@endsection
