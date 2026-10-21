<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\emprestimo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class emprestimoController extends Controller
{
    public function cadastro_html(request $request){
        return view('emprestimo_usuario');
    }

    public function salvar_emprestimo(request $request){
       $request->validate([
        'funcionario_id' => 'required|integer|exists:funcionarios,id',
        'chave_id' => 'required|integer|exists:chaves,id',
        'data_retirada' => 'required|date',
        'hora_retirada' => 'required|date_format:H:i',
        'data_devolucao' => 'required|date',
        'hora_devolucao' => 'required|date_format:H:i',
        'status' => 'required|string',
        ]);
        try {
            $emprestimo = new emprestimo();
            $emprestimo->funcionario_id = $request->input('funcionario_id');
            $emprestimo->chave_id = $request->input('chave_id');
            $emprestimo->data_retirada = $request->input('data_retirada');
            $emprestimo->hora_retirada = $request->input('hora_retirada');
            $emprestimo->data_devolucao = $request->input('data_devolucao');
            $emprestimo->hora_devolucao = $request->input('hora_devolucao');
            $emprestimo->status = $request->input('status');
            $emprestimo->save();
            return response()->json(['message' => 'emprestimo cadastrado com sucesso!', 'erro' => 'n'], 200);
        }catch(\throwable $th){
           return response()->json(['message' => 'Erro ao cadastrar emprestimo', 'erro' => 'S', 'msg_error' => $th->getMessage()], 200);
        }

    }

    public function ver_emprestimo(Request $request){
        $emprestimo = emprestimo::where('id_emprestimo', '=', $request->id)->first();
        if($emprestimo){
            return response()->json(['emprestimo' => $emprestimo, 'erro' => 'n'], 200);
        }else{
            return response()->json(['message' => 'Emprestimo não encontrado    ', 'erro' => 'S'], 200);
        }
    }
    public function listar_emprestimos(){
        $emprestimos = emprestimo::all();
        return response()->json(['emprestimos' => $emprestimos], 200);
    }
    public function listar_emprestimo_simples(request $request){
        $emprestimos = emprestimo::select('funcionario_id', 'chave_id', 'data_retirada', 'hora_retirada', 'data_devolucao', 'hora_devolucao', 'status')->get();
        return response()->json(['emprestimos' => $emprestimos], 200);
    }

    public function alterar_emprestimo(request $request){
       $request->validate([
            'id_emprestimo' => 'required|integer|exists:emprestimo,id_emprestimo',
            'funcionario_id' => 'required|integer|exists:funcionarios,id',
            'chave_id' => 'required|integer|exists:chaves,id',
            'data_retirada' => 'required|date',
            'hora_retirada' => 'required|date_format:H:i',
            'data_devolucao' => 'required|date',
            'hora_devolucao' => 'required|date_format:H:i',
            'status' => 'required|string',
            ]);
        ]);
        try {
            $emprestimo =  emprestimo::where('id_emprestimo', "=", $request->input('id_emprestimo'))->first();
            if (!$emprestimo) {
                return response()->json(['message' => 'Emprestimo não encontrado', 'erro' => 'S'], 200);
            }

          //  $emprestimo->funcionario_id = $request->input('funcionario_id');
          //  $emprestimo->chave_id = $request->input('chave_id');
            emprestimo::updateOrInsert(
                ['id_emprestimo' => $request->input('id_emprestimo')],
                ['funcionario_id' => $request->input('funcionario_id'), 'chave_id' => $request->input('chave_id'), 'data_retirada' => $request->input('data_retirada'), 'hora_retirada' => $request->input('hora_retirada'), 'data_devolucao' => $request->input('data_devolucao'), 'hora_devolucao' => $request->input('hora_devolucao'), 'status' => $request->input('status')]
            );
            return response()->json(['message' => 'Emprestimo alterado com sucesso!', 'erro' => 'n'], 200);

           
            
       }catch(\throwable $th){
           return response()->json(['message' => 'Erro ao cadastrar usuário', 'erro' => 'S', 'msg_error' => $th->getMessage()], 200);
        }

    }
 
public function deletar_emprestimo(request $request){
        $request->validate([
            'id' => 'required|integer|exists:emprestimo,id_emprestimo',
        ]);
        try {
            $emprestimo = DB::raw('DELETE FROM emprestimo WHERE id_emprestimo = ?', [$request->input('id')]);
         
            return response()->json(['message' => 'Emprestimo deletado com sucesso!','erro' =>'n'], 200);
        }catch(\throwable $th){
           return response()->json(['message' => 'Erro ao deletar emprestimo', 'erro' => 'S', 'msg_error' => $th->getMessage()], 200);
        }
    }

}