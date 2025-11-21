<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControleFinanceiro extends Model
{
    protected $table = 'controle_financeiro';

    public $incrementing = true;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idControle',
        'idUsuario',
        'tipo',
        'categoria',
        'data',
        'valor',
    ];

    public function scopeFilter($query, array $filters)
    {
        return $query

            ->when($filters['idControle'] ?? null, fn($q, $v) => $q->where('idControle', $v))
            ->when($filters['idUsuario'] ?? null, fn($q, $v) => $q->where('idUsuario', $v))
            ->when($filters['tipo'] ?? null, fn($q, $v) => $q->where('tipo', $v))
            ->when($filters['categoria'] ?? null, fn($q, $v) => $q->where('categoria', $v))
            ->when(($filters['dataInicio'] ?? null) && ($filters['dataFim'] ?? null), function ($q) use ($filters) {
                $q->whereBetween('data', [$filters['dataInicio'], $filters['dataFim']]);
            })
            ->when($filters['valor'] ?? null, fn($q, $v) => $q->where('valor', $v));
    }
}
