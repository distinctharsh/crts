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
        Schema::table('complaint_vertical', function (Blueprint $table) {
            $table->foreign(['complaint_id'])->references(['id'])->on('complaints')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['vertical_id'])->references(['id'])->on('verticals')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_vertical', function (Blueprint $table) {
            $table->dropForeign('complaint_vertical_complaint_id_foreign');
            $table->dropForeign('complaint_vertical_vertical_id_foreign');
        });
    }
};
