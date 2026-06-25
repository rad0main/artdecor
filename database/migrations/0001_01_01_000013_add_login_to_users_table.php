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
            $table->string('login', 50)->unique()->nullable()->after('name');
        });

        // Copy existing emails to login as default
        DB::statement("UPDATE users SET login = SPLIT_PART(email, '@', 1) WHERE login IS NULL");

        // Make email nullable since auth is now login-based
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('login');
            $table->string('email')->nullable(false)->change();
        });
    }
};
