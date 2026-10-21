<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Models\Chave;
use App\Models\Emprestimo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(): View
    {
        $loans = Emprestimo::with('chave')->latest('loaned_at')->paginate(15)->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function create(): View
    {
        $keys = Chave::query()->whereDoesntHave('emprestimos', fn ($query) => $query->whereNull('returned_at'))->orderBy('name')->get();

        return view('loans.create', compact('keys'));
    }

    public function store(StoreLoanRequest $request): RedirectResponse
    {
        $created = DB::transaction(function () use ($request): bool {
            $key = Chave::query()->lockForUpdate()->findOrFail($request->integer('key_id'));

            if ($key->emprestimos()->whereNull('returned_at')->exists()) {
                return false;
            }

            $key->emprestimos()->create($request->safe()->only(['person', 'note']) + ['loaned_at' => now()]);

            return true;
        });

        if (! $created) {
            return back()->withInput()->withErrors(['key_id' => 'Esta chave jÃ¡ estÃ¡ emprestada.']);
        }

        return redirect()->route('loans.index')->with('status', 'EmprÃ©stimo registrado com sucesso.');
    }

    public function markReturned(Emprestimo $loan): RedirectResponse
    {
        if ($loan->returned_at) {
            return back()->with('error', 'Esta movimentaÃ§Ã£o jÃ¡ foi encerrada.');
        }

        $loan->update(['returned_at' => now()]);

        return back()->with('status', 'DevoluÃ§Ã£o registrada com sucesso.');
    }
}
