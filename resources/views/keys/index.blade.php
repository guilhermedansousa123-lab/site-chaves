@extends('layouts.app')

@section('title', 'Chaves')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div><h1 class="page-title mb-1">Gerenciamento de chaves</h1><p class="page-subtitle mb-0">Cadastre e acompanhe a disponibilidade.</p></div>
    <a class="btn btn-primary" href="{{ route('keys.create') }}"><i class="bi bi-plus-lg me-1"></i> Nova chave</a>
</div>
<div class="card p-3 p-md-4">
    <form class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-3" method="GET" action="{{ route('keys.index') }}">
        <h2 class="h5 fw-bold mb-0">Chaves cadastradas</h2>
        <div class="d-flex gap-2"><input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Buscar chave ou local" aria-label="Buscar chave ou local"><button class="btn btn-outline-secondary" type="submit">Buscar</button></div>
    </form>
    <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Chave</th><th>Local</th><th>Status</th><th class="text-end">Ações</th></tr></thead><tbody>
        @forelse ($keys as $key)
            <tr><td class="fw-semibold">{{ $key->name }}</td><td>{{ $key->place }}</td><td><span class="badge {{ $key->active_loans_count ? 'text-bg-warning' : 'text-bg-success' }}">{{ $key->active_loans_count ? 'Emprestada' : 'Disponível' }}</span></td><td><div class="d-flex justify-content-end gap-2"><a class="btn btn-sm btn-outline-secondary" href="{{ route('keys.edit', $key) }}"><i class="bi bi-pencil" aria-hidden="true"></i><span class="visually-hidden">Editar {{ $key->name }}</span></a><form method="POST" action="{{ route('keys.destroy', $key) }}" data-confirm="Excluir esta chave? Chaves com movimentações não podem ser excluídas.">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash" aria-hidden="true"></i><span class="visually-hidden">Excluir {{ $key->name }}</span></button></form></div></td></tr>
        @empty
            <tr><td colspan="4" class="text-center text-secondary py-4">Nenhuma chave encontrada.</td></tr>
        @endforelse
    </tbody></table></div>
    @if ($keys->hasPages()) <div class="mt-3">{{ $keys->links() }}</div> @endif
</div>
@endsection
