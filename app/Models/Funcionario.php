<?php

namespace App\Models;

use Database\Factories\FuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Funcionario extends Model
{
    /** @use HasFactory<FuncionarioFactory> */
    use HasFactory;

    protected $fillable = ['nome', 'email', 'cargo'];

    public function emprestimos(): HasMany
    {
        return $this->hasMany(Emprestimo::class, 'funcionario_id');
    }
}
