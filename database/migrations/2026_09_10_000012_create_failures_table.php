<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('failures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->dateTime('failure_date');
            $table->string('failure_type', 100);
            $table->text('symptom')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('action_taken')->nullable();
            $table->decimal('downtime_hours', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->uuid('created_by');

            $table->index('asset_id', 'idx_failures_asset');
            $table->index('created_by', 'idx_failures_created_by');

            $table->foreign('asset_id', 'fk_failures_asset')
                ->references('id')->on('assets')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('created_by', 'fk_failures_created_by')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failures');
    }
};