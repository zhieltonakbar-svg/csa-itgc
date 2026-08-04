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
            $table->string('keterangan_frekuensi')->nullable()->after('control_description');
            $table->string('upti')->nullable()->after('keterangan_frekuensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('controls', function (Blueprint $table) {
            $table->dropColumn(['keterangan_frekuensi', 'upti']);
        });
    }
};
