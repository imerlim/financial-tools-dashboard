<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DocumentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth; // Adicione esta linha

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function uploadDocument(Request $request)
    {
        dd($request);
        try {
            $request->validate([
                'document' => 'required|file|max:10240',
            ]);

            // Usando Auth::id() para evitar o erro do VS Code
            $result = $this->documentService->processUpload(
                $request->file('document'),
                Auth::id()
            );

            return response()->json($result, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listDocuments()
    {
        try {
            $result = $this->documentService->getAllByUser(Auth::id());
            return response()->json($result, 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
