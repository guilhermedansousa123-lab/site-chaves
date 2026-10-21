<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    public function salvar_funcionario(request $request){
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:funcionarios',
            'senha' => 'required|string|min:8',
        ]);
        try {
            $funcionario = new funcionario();
            $funcionario->nome = $request->input('nome');
            $funcionario->email = $request->input('email');
            $funcionario->senha = bcrypt($request->input('senha'));
            $funcionario->save();
            return response()->json(['message' => 'Funcionario cadastrado com sucesso!', 'erro' => 'n'], 200);
        }catch(\throwable $th){
           return response()->json(['message' => 'Erro ao cadastrar funcionario', 'erro' => 'S', 'msg_error' => $th->getMessage()], 200);
        }

    }

    public function ver_funcionario(Request $request){
        $funcionario = funcionario::where('id', '=', $request->id)->first();
        if($funcionario){
            return response()->json(['funcionario' => $funcionario, 'erro' => 'n'], 200);
        }else{
            return response()->json(['message' => 'Funcionario não encontrado    ', 'erro' => 'S'], 200);
        }
    }

    public function deletar_funcionario(request $request){
        $request->validate([
            'id' => 'required|integer|exists:funcionarios,id',
        ]);
        try {
            $funcionario = DB::raw('DELETE FROM funcionarios WHERE id = ?', [$request->input('id')]);
         
            return response()->json(['message' => 'Funcionario deletado com sucesso!','erro' =>'n'], 200);
        }catch(\throwable $th){
           return response()->json(['message' => 'Erro ao deletar funcionario', 'erro' => 'S', 'msg_error' => $th->getMessage()], 200);
        }
    }

    public function alterar_funcionario(request $request){
        $request->validate([
            'id' => 'required|integer|exists:funcionarios,id',
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:funcionarios,email,'.$request->input('id'),
            'senha' => 'nullable|string|min:8',
        ]);
        try {
            $funcionario = funcionario::where('id', '=', $request->input('id'))->first();
            if (!$funcionario) {
                return response()->json(['message' => 'Funcionario não encontrado', 'erro' => 'S'], 200);
            }
            $funcionario->nome = $request->input('nome');
            $funcionario->email = $request->input('email');
            if ($request->filled('senha')) {
                $funcionario->senha = bcrypt($request->input('senha'));
            }
            $funcionario->save();
            return response()->json(['message' => 'Funcionario alterado com sucesso!', 'erro' => 'n'], 200);
        }catch(\throwable $th){
           return response()->json(['message' => 'Erro ao alterar funcionario', 'erro' => 'S', 'msg_error' => $th->getMessage()], 200);
        }
    }
}
