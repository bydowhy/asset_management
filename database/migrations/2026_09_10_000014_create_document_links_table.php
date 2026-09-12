<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id');
            $table->enum('entity_type', ['equipment', 'asset']);
            $table->uuid('entity_id');

            $table->unique(['document_id', 'entity_type', 'entity_id'], 'uk_document_links_unique');
            $table->index(['entity_type', 'entity_id'], 'idx_document_links_entity');

            $table->foreign('document_id', 'fk_document_links_document')
                ->references('id')->on('documents')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_links');
    }
};