<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialControl extends Model
{
    protected $table = 'financial_control';

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
        'type',
        'category',
        'date',
        'amountValue',
    ];

    public function scopeFilter($query, array $filters)
    {
        return $query

            ->when($filters['idControle'] ?? null, fn($q, $v) => $q->where('idControle', $v))
            ->when($filters['idUsuario'] ?? null, fn($q, $v) => $q->where('idUsuario', $v))
            ->when($filters['type'] ?? null, fn($q, $v) => $q->where('type', $v))
            ->when($filters['category'] ?? null, fn($q, $v) => $q->where('category', $v))
            ->when(($filters['dataInicio'] ?? null) && ($filters['dataFim'] ?? null), function ($q) use ($filters) {
                $q->whereBetween('date', [$filters['dataInicio'], $filters['dataFim']]);
            })
            ->when($filters['valor'] ?? null, fn($q, $v) => $q->where('valor', $v));
    }
}
