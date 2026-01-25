<?php

namespace App\Http\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // RESOLVE O ERRO DA IMAGEM E1C4F0
use Illuminate\Support\Facades\Log; // RESOLVE O ERRO DA IMAGEM E1C4F0
use Aws\Textract\TextractClient;

class DocumentService
{
    protected $user;
    /**
     * Envia o arquivo para o S3 e salva no MySQL
     */
    public function processUpload($file)
    {
        return DB::transaction(function () use ($file) {
            $user = Auth::user();
            // 1. Tenta salvar no S3 primeiro

            if ($file->getSize() > 5 * 1024 * 1024) {
                return ['error' => 2, 'msg' => 'File is too large. Max limit is 5MB.'];
            }

            $documentCount = Document::where('user_id', $user->id)->count();

            if ($documentCount >= 3) {
                return ['error' => 3, 'msg' => 'You have reached the limit of 3 files.'];
            }

            $path = $file->store('documents', 's3');

            if (!$path) {
                return ['error' => 1, 'msg' => 'Erro ao subir arquivo para o S3'];
            }

            $document = Document::create([
                'user_id'           => $user->id,
                'title'             => $file->getClientOriginalName(),
                'original_filename' => $file->getClientOriginalName(),
                's3_key'            => $path,
                'mime_type'         => $file->getMimeType(),
                'size'              => $file->getSize(),
                'status'            => 'completed',
                'extracted_text'    => 'Processing skipped for demo...',
            ]);

            // $this->extractText($document); 

            return $document;
        });
    }

    public function extractText($document)
    {
        $textract = new TextractClient([
            'region'      => config('filesystems.disks.s3.region'),
            'version'     => 'latest',
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);

        try {
            $result = $textract->detectDocumentText([
                'Document' => [
                    'S3Object' => [
                        'Bucket' => config('filesystems.disks.s3.bucket'),
                        'Name'   => $document->s3_key,
                    ],
                ],
            ]);

            $text = collect($result->get('Blocks'))
                ->where('BlockType', 'LINE')
                ->pluck('Text')
                ->implode(' ');

            $document->update([
                'extracted_text' => $text,
                'status' => 'completed'
            ]);
        } catch (\Exception $e) {
            // If there's a signature/permission error, we log it but don't stop the system
            Log::error("AWS Textract Error: " . $e->getMessage());
            $document->update(['status' => 'error']);
        }
    }

    public function getAllByUser($userId)
    {
        return Document::where('user_id', $userId)->latest()->get();
    }

    public function generateDownloadUrl($id, $userId)
    {
        // We fetch the document ensuring it belongs to the user
        $document = Document::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // In your generateDownloadUrl method:
        /** @var \Illuminate\Filesystem\S3FilesystemAdapter $disk */
        $disk = Storage::disk('s3');
        $url = $disk->temporaryUrl($document->s3_key, now()->addMinutes(15));

        return ['url' => $url];
    }

    public function deleteDocument($id, $userId)
    {
        $document = Document::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // 1. Remove from S3 first
        if (Storage::disk('s3')->exists($document->s3_key)) {
            Storage::disk('s3')->delete($document->s3_key);
        }

        // 2. Remove from the Database
        return $document->delete();
    }
}
