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
        Schema::table('controls', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('updated_at');
            $table->timestamp('reviewed_at')->nullable()->after('submitted_at');
            $table->timestamp('approved_at')->nullable()->after('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('controls', function (Blueprint $table) {
            $table->dropColumn(['submitted_at', 'reviewed_at', 'approved_at']);
        });
    }
};
