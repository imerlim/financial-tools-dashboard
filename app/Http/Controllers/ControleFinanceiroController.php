<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContatoMail;
use App\Models\Categoria;

class ControleFinanceiroController extends Controller
{
    public function createCategoria(Request $request)
    {
        dd($request);
    }
}
