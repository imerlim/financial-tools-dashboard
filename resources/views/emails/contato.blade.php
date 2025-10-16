<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Novo Contato</title>
</head>
<body>
    <h2>Nova mensagem de contato</h2>

    <p><strong>Nome:</strong> {{ $dados['nome'] ?? '' }}</p>
    <p><strong>Email:</strong> {{ $dados['email'] ?? '' }}</p>
    <p><strong>Assunto:</strong> {{ $dados['assunto'] ?? '' }}</p>

    <hr>

    <p><strong>Mensagem:</strong></p>
    <p>{!! nl2br(e($dados['mensagem'] ?? '')) !!}</p>
</body>
</html>
