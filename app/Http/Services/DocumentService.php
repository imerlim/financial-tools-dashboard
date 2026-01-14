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
        dd($file);
        // O método 'store' envia para o bucket 'merlim-dms-storage'
        $path = $file->store('documents', 's3');

        return Document::create([
            'user_id' => $userId,
            'title' => $file->getClientOriginalName(),
            's3_key' => $path,
            'status' => 'processing',
        ]);
    }

    public function getAllByUser($userId)
    {
        return Document::where('user_id', $userId)->latest()->get();
    }
}
