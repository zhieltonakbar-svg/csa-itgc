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
        if (Schema::hasTable('control_evidence') && !Schema::hasColumn('control_evidence', 'uploaded_by')) {
            Schema::table('control_evidence', function (Blueprint $table) {
                $table->string('uploaded_by')->nullable()->after('size');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('control_evidence') && Schema::hasColumn('control_evidence', 'uploaded_by')) {
            Schema::table('control_evidence', function (Blueprint $table) {
                $table->dropColumn('uploaded_by');
            });
        }
    }
};
