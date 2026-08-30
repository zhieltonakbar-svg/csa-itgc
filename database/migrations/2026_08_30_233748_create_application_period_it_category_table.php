<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_period_it_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_period_id')->constrained('application_periods')->cascadeOnDelete();
            $table->foreignId('it_category_id')->constrained('it_categories')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['application_period_id', 'it_category_id'], 'app_period_cat_unique');
        });

        // Data migration: copy existing relations from application_it_category 
        // into all existing application_periods for that application
        $periods = DB::table('application_periods')->get();
        $globalLinks = DB::table('application_it_category')->get()->groupBy('application_id');

        $inserts = [];
        $now = now();
        foreach ($periods as $period) {
            if (isset($globalLinks[$period->application_id])) {
                foreach ($globalLinks[$period->application_id] as $link) {
                    $inserts[] = [
                        'application_period_id' => $period->id,
                        'it_category_id' => $link->it_category_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        // Chunk inserts just in case it's a lot
        foreach (array_chunk($inserts, 500) as $chunk) {
            DB::table('application_period_it_category')->insert($chunk);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('application_period_it_category');
    }
};
