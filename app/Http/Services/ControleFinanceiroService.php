<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;


class ControleFinanceiroService
{
    public function createCategoria($novaCategoria, $userId)
    {
        $existe = Categoria::where('categoria', $novaCategoria)->exists();

        if ($existe) {
            return ['Error' => 1, 'msg' => 'Categoria já cadastrada.'];
        }

        $query = new Categoria;

        $query->categoria = $novaCategoria;
        $query->id_usuario = $userId;

        if (!$query->save()) {
            return ['Error' => 2, 'msg' => 'Erro ao salvar categoria.'];
        }

        return $query;
    }

    public function allCategoria()
    {
        $query = Categoria::orderBy('categoria', 'asc')->get();

        if ($query->isEmpty()) {
            return ['error' => 1, 'msg' => "Não foi possível encontrar o registro."];
        }

        return $query;
    }

    public function deleteCategoria($idCategoria)
    {
        $query = Categoria::where('id_categoria', '=', $idCategoria)->delete();

        if (!$query) {
            return ['error' => 1, 'msg' => "Erro ao excluir categoria."];
        }

        return $query;
    }
}
