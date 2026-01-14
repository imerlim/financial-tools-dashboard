<?php

namespace App\Http\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Aws\Textract\TextractClient;

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

    public function generateDownloadUrl($id, $userId)
    {
        // Buscamos o documento garantindo que pertence ao usuário
        $document = Document::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Gera a URL assinada para visualização privada no S3
        $url = Storage::disk('s3')->temporaryUrl(
            $document->s3_key,
            now()->addMinutes(15)
        );

        return ['url' => $url];
    }

    public function deleteDocument($id, $userId)
    {
        $document = Document::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // 1. Remove do S3 primeiro
        if (Storage::disk('s3')->exists($document->s3_key)) {
            Storage::disk('s3')->delete($document->s3_key);
        }

        // 2. Remove do Banco de Dados
        return $document->delete();
    }

    public function extractText($document)
    {
        $textract = new TextractClient([
            'region' => config('filesystems.disks.s3.region'),
            'version' => 'latest'
        ]);

        // Chama a IA da AWS para analisar o documento no S3
        $result = $textract->detectDocumentText([
            'Document' => [
                'S3Object' => [
                    'Bucket' => config('filesystems.disks.s3.bucket'),
                    'Name'   => $document->s3_key,
                ],
            ],
        ]);

        // Une todas as linhas de texto encontradas pela IA
        $text = "";
        foreach ($result->get('Blocks') as $block) {
            if ($block['BlockType'] == 'LINE') {
                $text .= $block['Text'] . " ";
            }
        }

        // Atualiza o banco para mudar o status na sua tabela Vue!
        $document->update([
            'extracted_text' => $text,
            'status' => 'completed'
        ]);
    }
}
