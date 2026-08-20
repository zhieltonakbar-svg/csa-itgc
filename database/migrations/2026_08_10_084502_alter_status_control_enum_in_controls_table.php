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
        DB::statement("ALTER TABLE controls MODIFY COLUMN status_control ENUM('not_started', 'drafting', 'ongoing_review', 'ongoing_approval', 'return_to_officer', 'return_to_reviewer', 'completed') DEFAULT 'not_started'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE controls MODIFY COLUMN status_control ENUM('not_started', 'ongoing_review', 'ongoing_approval', 'completed') DEFAULT 'not_started'");
    }
};
