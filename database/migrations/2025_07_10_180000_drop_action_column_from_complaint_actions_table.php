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
        if (Schema::hasColumn('complaint_actions', 'action')) {
            Schema::table('complaint_actions', function (Blueprint $table) {
                $table->dropColumn('action');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('complaint_actions', 'action')) {
            Schema::table('complaint_actions', function (Blueprint $table) {
                $table->string('action')->nullable()->after('assigned_to');
            });
        }
    }
}; 