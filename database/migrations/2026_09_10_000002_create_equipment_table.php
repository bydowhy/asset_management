<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('location_id');
            $table->string('tag', 50)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('equipment_type', 50)->nullable();

            $table->index('location_id', 'idx_equipment_location');

            $table->foreign('location_id', 'fk_equipment_location')
                ->references('id')->on('locations')
                ->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};