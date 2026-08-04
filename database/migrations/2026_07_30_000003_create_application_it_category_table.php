<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_it_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->foreignId('it_category_id')->constrained('it_categories')->cascadeOnDelete();
            // Completion status driven by the Excel master data mapping
            $table->enum('completion_status', ['complete', 'partial', 'not_complete'])
                  ->default('not_complete');
            $table->timestamps();

            $table->unique(['application_id', 'it_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_it_category');
    }
};
