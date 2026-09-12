<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_type_definitions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_type_id');
            $table->string('name', 100);
            $table->string('code', 50);
            $table->string('data_type', 20);
            $table->string('unit', 50)->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);

            $table->unique(['asset_type_id', 'code'], 'uk_asset_type_definitions_asset_type_code');
            $table->index('asset_type_id', 'idx_asset_type_definitions_asset_type');

            $table->foreign('asset_type_id', 'fk_asset_type_definitions_asset_type')
                ->references('id')->on('asset_types')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_type_definitions');
    }
};