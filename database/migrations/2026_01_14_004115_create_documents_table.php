<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Só cria se a tabela não existir
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->string('original_filename');
                $table->string('s3_key'); // Identificador único no S3
                $table->string('mime_type')->nullable();
                $table->bigInteger('size')->default(0);

                // Campos para o desafio de IA/Processamento
                $table->text('extracted_text')->nullable(); // Para o OCR
                $table->string('status')->default('pending'); // pending, processing, completed, error

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
