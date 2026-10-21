@extends('layouts.app')

@section('title', 'Movimentações')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div><h1 class="page-title mb-1">Histórico de movimentações</h1><p class="page-subtitle mb-0">Consulte empréstimos e devoluções registrados.</p></div>
    <a class="btn btn-primary" href="{{ route('loans.create') }}"><i class="bi bi-plus-lg me-1"></i> Registrar empréstimo</a>
</div>
<div class="card p-3 p-md-4"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Data</th><th>Chave</th><th>Responsável</th><th>Observação</th><th>Situação</th><th class="text-end">Ação</th></tr></thead><tbody>
    @forelse ($loans as $loan)
        <tr><td>{{ $loan->loaned_at->format('d/m/Y H:i') }}</td><td class="fw-semibold">{{ $loan->chave->name }}</td><td>{{ $loan->person }}</td><td>{{ $loan->note ?: '—' }}</td><td><span class="badge {{ $loan->returned_at ? 'text-bg-secondary' : 'text-bg-warning' }}">{{ $loan->returned_at ? 'Devolvida' : 'Em aberto' }}</span></td><td class="text-end">@if (! $loan->returned_at)<form method="POST" action="{{ route('loans.return', $loan) }}" data-confirm="Confirmar a devolução desta chave?">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-primary" type="submit">Registrar devolução</button></form>@else<span class="small text-secondary">{{ $loan->returned_at->format('d/m/Y H:i') }}</span>@endif</td></tr>
    @empty
        <tr><td colspan="6" class="text-center text-secondary py-4">Nenhuma movimentação registrada.</td></tr>
    @endforelse
</tbody></table></div>
@if ($loans->hasPages()) <div class="mt-3">{{ $loans->links() }}</div> @endif
</div>
@endsection
