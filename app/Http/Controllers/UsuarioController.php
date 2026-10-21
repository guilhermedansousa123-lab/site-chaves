<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function salvar_usuario(Request $request)
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $usuario = User::create([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
        ]);

        return response()->json([
            'message' => 'Usuário cadastrado com sucesso!',
            'usuario' => $usuario,
            'erro' => 'n',
        ], 201);
    }

    public function ver_usuario(Request $request)
    {
        $dados = $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        return response()->json([
            'usuario' => User::findOrFail($dados['id']),
            'erro' => 'n',
        ]);
    }

    public function listar_usuarios()
    {
        return response()->json([
            'usuarios' => User::all(),
            'erro' => 'n',
        ]);
    }

    public function listar_usuarios_simples()
    {
        return response()->json([
            'usuarios' => User::query()->select('id', 'name', 'email')->get(),
            'erro' => 'n',
        ]);
    }

    public function alterar_usuario(Request $request)
    {
        $dados = $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->input('id'))],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $usuario = User::findOrFail($dados['id']);
        $usuario->name = $dados['name'];
        $usuario->email = $dados['email'];

        if (! empty($dados['password'])) {
            $usuario->password = Hash::make($dados['password']);
        }

        $usuario->save();

        return response()->json([
            'message' => 'Usuário alterado com sucesso!',
            'usuario' => $usuario,
            'erro' => 'n',
        ]);
    }

    public function deletar_usuario(Request $request)
    {
        $dados = $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        User::findOrFail($dados['id'])->delete();

        return response()->json([
            'message' => 'Usuário deletado com sucesso!',
            'erro' => 'n',
        ]);
    }
}
