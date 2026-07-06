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
            $table->foreign(['parent_id'])->references(['id'])->on('verticals')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verticals', function (Blueprint $table) {
            $table->dropForeign('verticals_parent_id_foreign');
        });
    }
};
