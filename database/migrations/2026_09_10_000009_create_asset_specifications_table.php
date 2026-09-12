<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_specifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->uuid('definition_id');
            $table->text('value');

            $table->unique(['asset_id', 'definition_id'], 'uk_asset_specifications_asset_definition');
            $table->index('asset_id', 'idx_asset_specifications_asset');
            $table->index('definition_id', 'idx_asset_specifications_definition');

            $table->foreign('asset_id', 'fk_asset_specifications_asset')
                ->references('id')->on('assets')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('definition_id', 'fk_asset_specifications_definition')
                ->references('id')->on('asset_type_definitions')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_specifications');
    }
};