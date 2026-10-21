<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function show(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function update(UpdateUserRequest $request): UserResource
    {
        $user = $request->user();
        $user->update($request->validated());

        return new UserResource($user->refresh());
    }

    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(null, 204);
    }
}
