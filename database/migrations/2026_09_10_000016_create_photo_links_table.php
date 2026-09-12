<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photo_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('photo_id');
            $table->enum('entity_type', ['equipment', 'asset']);
            $table->uuid('entity_id');

            $table->unique(['photo_id', 'entity_type', 'entity_id'], 'uk_photo_links_unique');
            $table->index(['entity_type', 'entity_id'], 'idx_photo_links_entity');

            $table->foreign('photo_id', 'fk_photo_links_photo')
                ->references('id')->on('photos')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_links');
    }
};