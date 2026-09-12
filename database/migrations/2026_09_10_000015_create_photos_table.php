<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('file_name', 150);
            $table->string('file_path', 255);
            $table->integer('file_size');
            $table->string('caption', 255)->nullable();
            $table->dateTime('taken_at')->nullable();
            $table->uuid('uploaded_by');

            $table->index('uploaded_by', 'idx_photos_uploaded_by');

            $table->foreign('uploaded_by', 'fk_photos_uploaded_by')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};