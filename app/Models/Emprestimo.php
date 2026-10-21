<?php

namespace App\Models;

use Database\Factories\EmprestimoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprestimo extends Model
{
    /** @use HasFactory<EmprestimoFactory> */
    use HasFactory;

    protected $table = 'loans';

    protected $fillable = [
        'funcionario_id',
        'chave_id',
        'data_retirada',
        'hora_retirada',
        'data_devolucao',
        'hora_devolucao',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'loaned_at' => 'datetime',
            'returned_at' => 'datetime',
            'data_retirada' => 'date',
            'data_devolucao' => 'date',
        ];
    }

    public function chave(): BelongsTo
    {
        return $this->belongsTo(Chave::class, 'key_id');
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }
}
