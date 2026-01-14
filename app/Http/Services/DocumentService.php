<?php

namespace App\Http\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    /**
     * Envia o arquivo para o S3 e salva no MySQL
     */
    public function processUpload($file, $userId)
    {
        // O arquivo está sendo enviado corretamente para o S3!
        $path = $file->store('documents', 's3');

        return Document::create([
            'user_id'           => $userId,
            'title'             => $file->getClientOriginalName(),
            'original_filename' => $file->getClientOriginalName(), // Adicionado aqui
            's3_key'            => $path,
            'mime_type'         => $file->getMimeType(),           // Adicionado aqui
            'size'              => $file->getSize(),               // Adicionado aqui
            'status'            => 'processing',
        ]);
    }

    public function getAllByUser($userId)
    {
        return Document::where('user_id', $userId)->latest()->get();
    }
}
