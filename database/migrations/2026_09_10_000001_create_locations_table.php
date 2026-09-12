<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id')->nullable();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->string('description', 255)->nullable();

            $table->index('parent_id', 'idx_locations_parent');

            $table->foreign('parent_id', 'fk_locations_parent')
                ->references('id')->on('locations')
                ->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};