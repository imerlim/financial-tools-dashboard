<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Services\ControleFinanceiroService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ControleFinanceiroController extends Controller
{
    protected $controleFinanceiroService;

    public function __construct(ControleFinanceiroService $controleFinanceiroService)
    {
        $this->controleFinanceiroService = $controleFinanceiroService;
    }

    public function createCategoria(Request $request)
    {
        try {
            $result = $this->controleFinanceiroService->createCategoria($request->novaCategoria, $request->userId);
            return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function allCategoria()
    {
        try {
            $result = $this->controleFinanceiroService->allCategoria();
            return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteCategoria(Request $request)
    {
        try {
            $result = $this->controleFinanceiroService->deleteCategoria($request->idCategoria);
            return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
