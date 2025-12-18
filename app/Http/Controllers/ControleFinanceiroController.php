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

    public function createCategory(Request $request)
    {
        try {
            $result = $this->controleFinanceiroService->createCategory($request->novaCategoria, $request->userId);
            return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createFinancialControl(Request $request)
    {
        try {
            $result = $this->controleFinanceiroService->createFinancialControl($request->selectType, $request->amountValue, $request->category, $request->date);
            return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function buscaControleFinanceiro(Request $request)
    {
        try {
            $result = $this->controleFinanceiroService->buscaControleFinanceiro($request->idControle, $request->tipo, $request->category, $request->dataInicio, $request->dataFim, $request->amountValue);
            return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function allCategory()
    {
        try {
            $result = $this->controleFinanceiroService->allCategory();
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
            $result = $this->controleFinanceiroService->deleteCategoria($request->idCategory);
            return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
