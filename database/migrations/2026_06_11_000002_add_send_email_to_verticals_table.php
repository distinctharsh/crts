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
        Schema::table('verticals', function (Blueprint $table) {
            $table->boolean('send_email')->default(true)->after('short_form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verticals', function (Blueprint $table) {
            $table->dropColumn('send_email');
        });
    }
};
