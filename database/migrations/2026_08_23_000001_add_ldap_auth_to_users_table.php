<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('auth_type', ['local', 'ldap'])
                ->default('local')
                ->after('email');

            $table->string('username')
                ->nullable()
                ->unique()
                ->after('auth_type');
        });

        // LDAP users don't need a local password, and may not
        // have an email yet — relax both columns to nullable.
        // Raw SQL avoids requiring doctrine/dbal just for this.
        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NULL');
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['auth_type', 'username']);
        });

        DB::statement("UPDATE users SET password = '' WHERE password IS NULL");
        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
    }
};
