<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\FinancialControl;

class ControleFinanceiroService
{
    protected $user;

    public function createCategory($novaCategoria, $userId)
    {
        $existe = Category::where('category', $novaCategoria)->exists();

        if ($existe) {
            return ['Error' => 1, 'msg' => 'Category already registered.'];
        }

        $query = new Category;

        $query->category = $novaCategoria;
        $query->idUser = $userId;

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
        $query->type = $selectType;
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

        $somaPorMes = FinancialControl::filter($filters)
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month,
            SUM(CASE WHEN TYPE = "I" THEN amountValue ELSE 0 END) as total_income,
            SUM(CASE WHEN TYPE = "E" THEN amountValue ELSE 0 END) as total_expense
            ')
            ->groupBy('month');

        $somaPorMesIncome = (clone $somaPorMes)
            ->orderBy('total_income', 'desc')
            ->get();

        $somaPorMesExpense = (clone $somaPorMes)
            ->orderBy('total_expense', 'desc')
            ->get();

        if ($query->isEmpty()) {
            return ['error' => 1, 'msg' => "Could not find the record."];
        }

        return [
            'query' => $query,
            'somaCategoria' => $somaCategoria,
            'somaPorMesIncome' => $somaPorMesIncome,
            'somaPorMesExpense' => $somaPorMesExpense,
        ];
    }

    public function allCategory()
    {
        $user = Auth::user();
        $query = Category::where('idUser', '=', $user->id)->orderBy('category', 'asc')->get();

        if ($query->isEmpty()) {
            return ['error' => 1, 'msg' => "Could not find the record."];
        }

        return $query;
    }

    public function deleteCategoria($idCategory)
    {
        $query = Category::where('idCategory', '=', $idCategory)->delete();

        if (!$query) {
            return ['error' => 1, 'msg' => "Error deleting category."];
        }

        return $query;
    }
}
