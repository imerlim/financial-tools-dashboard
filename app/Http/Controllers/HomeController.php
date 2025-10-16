<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContatoMail;

class HomeController extends Controller
{
    public function enviaMensagem(Request $request)
    {
        $dados = [
            'nome' => $request->nome,
            'email' => $request->email,
            'assunto' => $request->assunto,
            'mensagem' => $request->mensagem,
        ];

        Mail::to('aminiaproducoes@gmail.com')->send(new ContatoMail($dados));

        return response()->json(['ok' => true, 'mensagem' => 'E-mail enviado (registrado no log).']);
    }
}
