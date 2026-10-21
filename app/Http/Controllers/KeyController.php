<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKeyRequest;
use App\Http\Requests\UpdateKeyRequest;
use App\Models\Chave;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KeyController extends Controller
{
    public function index(): View
    {
        $keys = Chave::query()->withCount(['emprestimos as active_loans_count' => fn ($query) => $query->whereNull('returned_at')])
            ->when(request('search'), fn ($query, $search) => $query->where(fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('place', 'like', "%{$search}%")))
            ->orderBy('name')->paginate(12)->withQueryString();

        return view('keys.index', compact('keys'));
    }

    public function create(): View
    {
        return view('keys.form', ['key' => new Chave]);
    }

    public function store(StoreKeyRequest $request): RedirectResponse
    {
        Chave::create($request->validated());

        return redirect()->route('keys.index')->with('status', 'Chave cadastrada com sucesso.');
    }

    public function edit(Chave $key): View
    {
        return view('keys.form', compact('key'));
    }

    public function update(UpdateKeyRequest $request, Chave $key): RedirectResponse
    {
        $key->update($request->validated());

        return redirect()->route('keys.index')->with('status', 'Chave atualizada com sucesso.');
    }

    public function destroy(Chave $key): RedirectResponse
    {
        try {
            $key->delete();
        } catch (QueryException) {
            return back()->with('error', 'A chave possui movimentações e não pode ser excluída.');
        }

        return redirect()->route('keys.index')->with('status', 'Chave excluída com sucesso.');
    }

    public function ver_chaves(Request $request)
    {
       $key = Chave::all();
       return response()->json(['chaves' => $chaves, 'erro' => 'n'], 200);
    }
}
