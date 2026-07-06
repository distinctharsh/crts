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
        Schema::table('complaint_actions', function (Blueprint $table) {
            $table->foreign(['complaint_id'])->references(['id'])->on('complaints')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['status_id'])->references(['id'])->on('statuses')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_actions', function (Blueprint $table) {
            $table->dropForeign('complaint_actions_complaint_id_foreign');
            $table->dropForeign('complaint_actions_status_id_foreign');
        });
    }
};
