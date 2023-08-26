<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    // use HasFactory;

    protected $fillable = [
        'tecnico_nome',
        'data_solicitacao',
        'prazo_atendimento',
        'endereco_atendimento',
        'status',
        'observacao_atendimento'
    ];
}
