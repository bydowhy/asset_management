<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_relationships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('source_asset_id');
            $table->uuid('target_asset_id');
            $table->uuid('relationship_type_id');
            $table->dateTime('valid_from');
            $table->dateTime('valid_to')->nullable();
            $table->text('description')->nullable();

            $table->unique(
                ['source_asset_id', 'target_asset_id', 'relationship_type_id', 'valid_from'],
                'uk_asset_relationships_unique'
            );
            $table->index('source_asset_id', 'idx_asset_relationships_source');
            $table->index('target_asset_id', 'idx_asset_relationships_target');
            $table->index('relationship_type_id', 'idx_asset_relationships_type');

            $table->foreign('source_asset_id', 'fk_asset_relationships_source')
                ->references('id')->on('assets')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('target_asset_id', 'fk_asset_relationships_target')
                ->references('id')->on('assets')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('relationship_type_id', 'fk_asset_relationships_type')
                ->references('id')->on('relationship_types')
                ->onDelete('restrict')->onUpdate('restrict');
        });

        DB::statement('ALTER TABLE `asset_relationships` ADD CONSTRAINT `chk_asset_relationships_valid` CHECK (`valid_to` IS NULL OR `valid_to` >= `valid_from`)');
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_relationships');
    }
};