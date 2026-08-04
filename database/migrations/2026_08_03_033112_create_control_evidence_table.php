<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('control_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_id')->constrained('controls')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_evidence');
    }
};
