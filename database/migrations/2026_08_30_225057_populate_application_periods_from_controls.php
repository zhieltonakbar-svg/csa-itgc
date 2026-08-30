<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existing = DB::table('controls')
            ->select('application_id', 'year', 'quarter')
            ->distinct()
            ->whereNotNull('application_id')
            ->whereNotNull('year')
            ->whereNotNull('quarter')
            ->get();
            
        foreach ($existing as $period) {
            DB::table('application_periods')->insertOrIgnore([
                'application_id' => $period->application_id,
                'year' => $period->year,
                'quarter' => $period->quarter,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('controls', function (Blueprint $table) {
            //
        });
    }
};
