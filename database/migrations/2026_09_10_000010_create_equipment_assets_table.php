<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipment_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('equipment_id');
            $table->uuid('asset_id');
            $table->string('relationship_role', 50);
            $table->dateTime('installed_at');
            $table->dateTime('removed_at')->nullable();
            $table->text('notes')->nullable();

            $table->unique(['equipment_id', 'asset_id', 'installed_at'], 'uk_equipment_assets_equipment_asset_installed');
            $table->index('equipment_id', 'idx_equipment_assets_equipment');
            $table->index('asset_id', 'idx_equipment_assets_asset');

            $table->foreign('equipment_id', 'fk_equipment_assets_equipment')
                ->references('id')->on('equipment')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('asset_id', 'fk_equipment_assets_asset')
                ->references('id')->on('assets')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement('ALTER TABLE `equipment_assets` ADD CONSTRAINT `chk_equipment_assets_removed` CHECK (`removed_at` IS NULL OR `removed_at` >= `installed_at`)');
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_assets');
    }
};