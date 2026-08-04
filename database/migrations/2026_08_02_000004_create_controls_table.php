<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Controls table — stores every row from the imported Excel file.
     *
     * Columns mirror the Excel file exactly:
     *   uic                → UIC
     *   application_id     → FK → applications
     *   it_category_id     → FK → it_categories
     *   it_control_id      → IT Control ID  (e.g. C-IT-01)
     *   control_description→ Control Description
     *   status_control     → Status Control (workflow state)
     *   year               → Assessment year  (e.g. 2026)
     *   quarter            → Assessment quarter (q1–q4)
     */
    public function up(): void
    {
        Schema::create('controls', function (Blueprint $table) {
            $table->id();

            $table->string('uic')->nullable();

            $table->foreignId('application_id')
                  ->constrained('applications')
                  ->cascadeOnDelete();

            $table->foreignId('it_category_id')
                  ->constrained('it_categories')
                  ->cascadeOnDelete();

            $table->string('it_control_id');          // e.g. C-IT-01
            $table->text('control_description');

            /*
             * Status Control — exact values from the Excel workflow:
             *   not_started      → "Not Started Yet"
             *   ongoing_review   → "On Going Review"
             *   ongoing_approval → "On Going Approval"
             *   completed        → "Completed"
             */
            $table->enum('status_control', [
                'not_started',
                'ongoing_review',
                'ongoing_approval',
                'completed',
            ])->default('not_started');

            $table->string('year', 4)->default(date('Y'));
            $table->string('quarter', 2)->default('q1');   // q1 | q2 | q3 | q4

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controls');
    }
};
