<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Chave;
use App\Models\Emprestimo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $loans = Emprestimo::query()->with('chave')
            ->when(request('status') === 'open', fn ($query) => $query->whereNull('returned_at'))
            ->when(request('status') === 'returned', fn ($query) => $query->whereNotNull('returned_at'))
            ->when(request('search'), fn ($query, $search) => $query->where('person', 'like', "%{$search}%"))
            ->latest('loaned_at')->paginate(min(max((int) request('per_page', 15), 1), 100));

        return LoanResource::collection($loans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request): LoanResource|JsonResponse
    {
        $loan = DB::transaction(function () use ($request): ?Emprestimo {
            $key = Chave::query()->lockForUpdate()->findOrFail($request->integer('key_id'));

            if ($key->emprestimos()->whereNull('returned_at')->exists()) {
                return null;
            }

            return $key->emprestimos()->create($request->safe()->only(['person', 'note']) + ['loaned_at' => now()]);
        });

        if (! $loan) {
            return response()->json(['message' => 'A chave jÃ¡ estÃ¡ emprestada.'], 409);
        }

        return (new LoanResource($loan->load('chave')))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Emprestimo $loan): LoanResource
    {
        return new LoanResource($loan->load('chave'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanRequest $request, Emprestimo $loan): LoanResource|JsonResponse
    {
        if ($loan->returned_at) {
            return response()->json(['message' => 'MovimentaÃ§Ãµes concluÃ­das nÃ£o podem ser alteradas.'], 409);
        }

        $data = $request->validated();

        if (isset($data['key_id']) && (int) $data['key_id'] !== $loan->key_id) {
            $key = Chave::query()->lockForUpdate()->findOrFail($data['key_id']);

            if ($key->emprestimos()->whereNull('returned_at')->exists()) {
                return response()->json(['message' => 'A chave jÃ¡ estÃ¡ emprestada.'], 409);
            }
        }

        $loan->update($data);

        return new LoanResource($loan->refresh()->load('chave'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emprestimo $loan): JsonResponse
    {
        if ($loan->returned_at) {
            return response()->json(['message' => 'O histÃ³rico de devoluÃ§Ãµes nÃ£o pode ser excluÃ­do.'], 409);
        }

        $loan->delete();

        return response()->json(null, 204);
    }

    public function markReturned(Emprestimo $loan): LoanResource|JsonResponse
    {
        if ($loan->returned_at) {
            return response()->json(['message' => 'Esta movimentaÃ§Ã£o jÃ¡ foi encerrada.'], 409);
        }

        $loan->update(['returned_at' => now()]);

        return new LoanResource($loan->refresh()->load('chave'));
    }
}
