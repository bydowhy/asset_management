<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('action', 50);
            $table->string('entity_type', 50);
            $table->uuid('entity_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->dateTime('created_at');

            $table->index('user_id', 'idx_audit_logs_user');
            $table->index(['entity_type', 'entity_id'], 'idx_audit_logs_entity');

            $table->foreign('user_id', 'fk_audit_logs_user')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};