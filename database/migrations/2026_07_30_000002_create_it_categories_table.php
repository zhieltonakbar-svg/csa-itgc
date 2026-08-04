<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('it_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->default('bi-shield-lock');   // Bootstrap Icon class
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_categories');
    }
};
