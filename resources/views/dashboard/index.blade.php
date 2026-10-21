@extends('layouts.app')

@section('title', 'Painel')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div><h1 class="page-title mb-1">Painel</h1><p class="page-subtitle mb-0">Painel de empréstimos e devoluções.</p></div>
    <a class="btn btn-primary" href="{{ route('loans.create') }}"><i class="bi bi-arrow-left-right me-1"></i> Registrar empréstimo</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-4"><div class="card p-3 p-lg-4 h-100"><span class="metric-label">Empréstimos em aberto</span><div class="metric-value text-warning mt-2">{{ $activeCount }}</div><span class="metric-label">Aguardando devolução</span></div></div>
</div>
<div class="card p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Movimentações recentes</h2><a class="btn btn-sm btn-outline-secondary" href="{{ route('loans.index') }}">Ver histórico</a></div>
    <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Chave</th><th>Responsável</th><th>Data e hora</th><th>Situação</th></tr></thead><tbody>
        @forelse ($recentLoans as $loan)
            <tr><td class="fw-semibold">{{ $loan->chave->name }}</td><td>{{ $loan->person }}</td><td>{{ $loan->loaned_at->format('d/m/Y H:i') }}</td><td><span class="badge {{ $loan->returned_at ? 'text-bg-secondary' : 'text-bg-warning' }}">{{ $loan->returned_at ? 'Devolvida' : 'Em aberto' }}</span></td></tr>
        @empty
            <tr><td colspan="4" class="text-center text-secondary py-4">Nenhuma movimentação registrada.</td></tr>
        @endforelse
    </tbody></table></div>
</div>
@endsection
