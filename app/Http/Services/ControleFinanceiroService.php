<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Categoria;
use App\Models\ControleFinanceiro;

class ControleFinanceiroService
{
    protected $user;

    public function createCategoria($novaCategoria, $userId)
    {
        $existe = Categoria::where('categoria', $novaCategoria)->exists();

        if ($existe) {
            return ['Error' => 1, 'msg' => 'Categoria já cadastrada.'];
        }

        $query = new Categoria;

        $query->categoria = $novaCategoria;
        $query->idUsuario = $userId;

        if (!$query->save()) {
            return ['Error' => 2, 'msg' => 'Erro ao salvar registro.'];
        }

        return $query;
    }

    public function createControleFinanceiro($selectTipo, $valor, $categoria, $data)
    {
        $user = Auth::user();

        $query = new ControleFinanceiro;

        $query->idUsuario = $user->id;
        $query->tipo1 = $selectTipo;
        $query->categoria = $categoria;
        $query->data = $data;
        $query->valor = $valor;

        if (!$query->save()) {
            return ['Error' => 1, 'msg' => 'Erro ao salvar registro.'];
        }

        return $query;
    }

    public function buscaControleFinanceiro($idControle, $tipo, $categoria, $dataInicio, $dataFim, $valor)
    {
        $user = Auth::user();
        $idUsuario = $user->id;
        $filters = array_filter([
            'idControle' => $idControle,
            'idUsuario' => $idUsuario,
            'tipo' => $tipo,
            'categoria' => $categoria,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'valor' => $valor,
        ], fn($v) => $v !== null && $v !== '');

        $query = ControleFinanceiro::filter($filters)->get();

        $somaCategoria = ControleFinanceiro::filter($filters)
            ->select('categoria')
            ->selectRaw('SUM(valor) as valor')
            ->groupBy('categoria')
            ->orderBy('valor', 'desc')
            ->get();

        if ($query->isEmpty()) {
            return ['error' => 1, 'msg' => "Não foi possível encontrar o registro."];
        }

        return [
            'query' => $query,
            'somaCategoria' => $somaCategoria
        ];
    }

    public function allCategoria()
    {
        $user = Auth::user();
        $query = Categoria::where('idUsuario', '=', $user->id)->orderBy('categoria', 'asc')->get();

        if ($query->isEmpty()) {
            return ['error' => 1, 'msg' => "Não foi possível encontrar o registro."];
        }

        return $query;
    }

    public function deleteCategoria($idCategoria)
    {
        $query = Categoria::where('idCategoria', '=', $idCategoria)->delete();

        if (!$query) {
            return ['error' => 1, 'msg' => "Erro ao excluir categoria."];
        }

        return $query;
    }
}
