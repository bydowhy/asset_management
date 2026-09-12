<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->uuid('document_type_id');
            $table->string('file_path', 255);
            $table->integer('file_size');
            $table->text('description')->nullable();
            $table->uuid('uploaded_by');

            $table->index('document_type_id', 'idx_documents_type');
            $table->index('uploaded_by', 'idx_documents_uploaded_by');

            $table->foreign('document_type_id', 'fk_documents_document_type')
                ->references('id')->on('document_types')
                ->onDelete('restrict')->onUpdate('restrict');

            $table->foreign('uploaded_by', 'fk_documents_uploaded_by')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};