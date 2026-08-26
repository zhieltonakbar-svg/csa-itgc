<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('controls', function (Blueprint $table) {
            $table->enum('review_result', [
                'effective',
                'partially_effective',
                'ineffective',
            ])
                ->nullable()
                ->after('approver_notes');
        });
    }

    public function down(): void
    {
        Schema::table('controls', function (Blueprint $table) {
            $table->dropColumn('review_result');
        });
    }
};
