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
        'tipo',
        'categoria',
        'data',
        'valor',
    ];
}
