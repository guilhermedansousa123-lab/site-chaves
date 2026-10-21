<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKeyRequest;
use App\Http\Requests\UpdateKeyRequest;
use App\Http\Resources\KeyResource;
use App\Models\Chave;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KeyController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $chaves = Chave::query()
            ->withCount(['emprestimos as active_loans_count' => fn ($query) => $query->whereNull('returned_at')])
            ->orderBy('name')
            ->paginate(min(max((int) request('per_page', 15), 1), 100));

        return KeyResource::collection($chaves);
    }

    public function store(StoreKeyRequest $request): KeyResource|JsonResponse
    {
        $chave = Chave::create($request->validated());

        return (new KeyResource($chave))->response()->setStatusCode(201);
    }

    public function show(Chave $key): KeyResource
    {
        $key->loadCount(['emprestimos as active_loans_count' => fn ($query) => $query->whereNull('returned_at')]);

        return new KeyResource($key);
    }

    public function update(UpdateKeyRequest $request, Chave $key): KeyResource
    {
        $key->update($request->validated());

        return new KeyResource($key->refresh());
    }

    public function destroy(Chave $key): JsonResponse
    {
        if ($key->emprestimos()->exists()) {
            return response()->json(['message' => 'A chave possui emprestimos registrados e nao pode ser excluida.'], 409);
        }

        try {
            $key->delete();
        } catch (QueryException) {
            return response()->json(['message' => 'A chave possui emprestimos registrados e nao pode ser excluida.'], 409);
        }

        return response()->json(null, 204);
    }
}
