<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Categoria;
use App\Models\FinancialControl;

class ControleFinanceiroService
{
    protected $user;

    public function createCategory($novaCategoria, $userId)
    {
        $existe = Categoria::where('category', $novaCategoria)->exists();

        if ($existe) {
            return ['Error' => 1, 'msg' => 'Category already registered.'];
        }

        $query = new Categoria;

        $query->category = $novaCategoria;
        $query->idUsuario = $userId;

        if (!$query->save()) {
            return ['Error' => 2, 'msg' => 'Error saving record.'];
        }

        return $query;
    }

    public function createFinancialControl($selectType, $amountValue, $category, $date)
    {
        $user = Auth::user();

        $query = new FinancialControl;

        $query->idUsuario = $user->id;
        $query->tipo1 = $selectType;
        $query->category = $category;
        $query->date = $date;
        $query->amountValue = $amountValue;

        if (!$query->save()) {
            return ['Error' => 1, 'msg' => 'Error saving record.'];
        }

        return $query;
    }

    public function buscaControleFinanceiro($idControle, $tipo, $category, $dataInicio, $dataFim, $amountValue)
    {
        $user = Auth::user();
        $idUsuario = $user->id;
        $filters = array_filter([
            'idControle' => $idControle,
            'idUsuario' => $idUsuario,
            'tipo' => $tipo,
            'category' => $category,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'amountValue' => $amountValue,
        ], fn($v) => $v !== null && $v !== '');

        $query = FinancialControl::filter($filters)->get();

        $somaCategoria = FinancialControl::filter($filters)
            ->select('category')
            ->selectRaw('SUM(amountValue) as amountValue')
            ->groupBy('category')
            ->orderBy('amountValue', 'desc')
            ->get();

        if ($query->isEmpty()) {
            return ['error' => 1, 'msg' => "Could not find the record."];
        }

        return [
            'query' => $query,
            'somaCategoria' => $somaCategoria
        ];
    }

    public function allCategoria()
    {
        $user = Auth::user();
        $query = Categoria::where('idUsuario', '=', $user->id)->orderBy('category', 'asc')->get();

        if ($query->isEmpty()) {
            return ['error' => 1, 'msg' => "Could not find the record."];
        }

        return $query;
    }

    public function deleteCategoria($idCategoria)
    {
        $query = Categoria::where('idCategoria', '=', $idCategoria)->delete();

        if (!$query) {
            return ['error' => 1, 'msg' => "Error deleting category."];
        }

        return $query;
    }
}
