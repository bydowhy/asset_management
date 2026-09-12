<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('asset_code', 50)->unique();
            $table->uuid('asset_type_id');
            $table->string('manufacturer', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->enum('status', ['active', 'inactive', 'scrapped']);
            $table->text('description')->nullable();

            $table->index('asset_type_id', 'idx_assets_asset_type');

            $table->foreign('asset_type_id', 'fk_assets_asset_type')
                ->references('id')->on('asset_types')
                ->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};