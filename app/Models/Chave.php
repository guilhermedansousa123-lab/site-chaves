<?php

namespace App\Models;

use Database\Factories\ChaveFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chave extends Model
{

    protected $table = 'keys';

    protected $attributes = [
        'status' => 'Disponível',
    ];

    protected $fillable = ['name', 'place', 'status'];

    public function emprestimos(): HasMany
    {
        return $this->hasMany(Emprestimo::class, 'key_id');
    }
}