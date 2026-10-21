<?php

use App\Http\Controllers\Api\V1\KeyController as ApiKeyController;
use App\Http\Controllers\Api\V1\LoanController;
use App\Http\Controllers\Api\V1\TokenController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeyController;

Route::prefix('v1')->group(function (): void {
    Route::post('/token', [TokenController::class, 'store']);
    Route::post('/users', [UserController::class, 'store']);

    Route::apiResource('chaves', ApiKeyController::class)->parameters(['chaves' => 'key']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::delete('/token', [TokenController::class, 'destroy']);
        Route::get('/user', [UserController::class, 'show']);
        Route::patch('/user', [UserController::class, 'update']);
        Route::delete('/user', [UserController::class, 'destroy']);

        Route::apiResource('keys', ApiKeyController::class);
        Route::apiResource('loans', LoanController::class);
        Route::post('/loans/{loan}/return', [LoanController::class, 'markReturned']);
    });
});

Route::apiResource('chaves', ApiKeyController::class)->parameters(['chaves' => 'key']);

Route::post('/salvar_usuario', [UsuarioController::class, 'salvar_usuario']);
Route::get('/ver_usuario', [UsuarioController::class, 'ver_usuario']);
Route::get('/usuarios', [UsuarioController::class, 'listar_usuarios']);
Route::get('/usuarios/simples', [UsuarioController::class, 'listar_usuarios_simples']);
Route::put('/alterar_usuario', [UsuarioController::class, 'alterar_usuario']);
Route::delete('/deletar_usuario', [UsuarioController::class, 'deletar_usuario']);

Route::post('/salvar_funcionario', [FuncionarioController::class, 'salvar_funcionario']);
Route::get('/ver_funcionario', [FuncionarioController::class, 'ver_funcionario']);
Route::delete('/deletar_funcionario', [FuncionarioController::class, 'deletar_funcionario']);
Route::put('/alterar_funcionario', [FuncionarioController::class, 'alterar_funcionario']);

Route::post('/salvar_chaves', [LoanController::class, 'salvar_chaves']);
Route::get('/ver_chaves', [KeyController::class, 'ver_chaves']);


Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/salvar_emprestimo', [LoanController::class, 'store']);
    Route::get('/emprestimos', [LoanController::class, 'index']);
    Route::get('/emprestimos/simples', [LoanController::class, 'index']);
    Route::get('/emprestimos/{loan}', [LoanController::class, 'show']);
    Route::put('/emprestimos/{loan}', [LoanController::class, 'update']);
    Route::delete('/emprestimos/{loan}', [LoanController::class, 'destroy']);
});

