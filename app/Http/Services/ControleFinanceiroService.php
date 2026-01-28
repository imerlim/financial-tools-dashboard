<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\FinancialControl;

class ControleFinanceiroService
{
    protected $user;

    public function createCategory($novaCategoria)
    {
        $user = Auth::user();
        $existe = Category::where('category', $novaCategoria)->where('idUser', '=', $user->id)->exists();

        if ($existe) {
            return ['Error' => 1, 'msg' => 'Category already registered.'];
        }

        $query = new Category;

        $query->category = $novaCategoria;
        $query->idUser = $user->id;

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

        // 🔥 Invalida todos os caches de busca financeira desse usuário
        Cache::tags(['user_finances_' . $user->id])->flush();

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

        $cacheKey = 'finance_search_' . md5(json_encode($filters));

        Log::info('[Redis] Cache key gerada', ['key' => $cacheKey]);

        if (Cache::tags(['user_finances_' . $user->id])->has($cacheKey)) {
            Log::info('[Redis] Resultado retornado do cache', ['key' => $cacheKey]);
        } else {
            Log::info('[Redis] Cache MISS - buscando no banco', ['key' => $cacheKey]);
        }

        return Cache::tags(['user_finances_' . $user->id])
            ->remember($cacheKey, now()->addHour(), function () use ($filters, $cacheKey) {
                Log::info('[Redis] Executando queries no banco', ['key' => $cacheKey]);

                $query = FinancialControl::filter($filters)->get()->toArray();

                $somaCategoria = FinancialControl::filter($filters)
                    ->select('category')
                    ->selectRaw('SUM(amountValue) as amountValue')
                    ->groupBy('category')
                    ->orderBy('amountValue', 'desc')
                    ->get()
                    ->toArray();

                $somaPorMes = FinancialControl::filter($filters)
                    ->selectRaw('
                        DATE_FORMAT(date, "%Y-%m") as month,
                        SUM(CASE WHEN type = "I" THEN amountValue ELSE 0 END) as total_income,
                        SUM(CASE WHEN type = "E" THEN amountValue ELSE 0 END) as total_expense
                    ')
                    ->groupBy('month');

                $somaPorMesIncome = (clone $somaPorMes)->orderBy('total_income', 'desc')->get()->toArray();
                $somaPorMesExpense = (clone $somaPorMes)->orderBy('total_expense', 'desc')->get()->toArray();

                if (empty($query)) {
                    Log::warning('[Redis] Consulta vazia', ['key' => $cacheKey]);
                    return ['error' => 1, 'msg' => "Could not find the record."];
                }

                Log::info('[Redis] Dados armazenados no cache', [
                    'key' => $cacheKey,
                    'total_registros' => count($query)
                ]);

                return [
                    'query' => $query,
                    'somaCategoria' => $somaCategoria,
                    'somaPorMesIncome' => $somaPorMesIncome,
                    'somaPorMesExpense' => $somaPorMesExpense,
                ];
            });
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
        $user = Auth::user();
        $query = Category::where('idCategory', '=', $idCategory)->where('idUser', '=', $user->id)->delete();

        if (!$query) {
            return ['error' => 1, 'msg' => "Error deleting category."];
        }

        return $query;
    }

    public function deleteFinancialControl($idFiancialData)
    {
        $user = Auth::user();
        $query = FinancialControl::where('idControle', '=', $idFiancialData)->where('idUsuario', '=', $user->id)->delete();

        if (!$query) {
            return ['error' => 1, 'msg' => "Error deleting category."];
        }

        Cache::tags(['user_finances_' . $user->id])->flush();

        return $query;
    }
}
