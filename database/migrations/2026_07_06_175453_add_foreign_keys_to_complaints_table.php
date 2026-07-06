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
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreign(['assigned_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['assigned_to'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['network_type_id'])->references(['id'])->on('network_types')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['request_type_id'])->references(['id'])->on('request_types')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['section_id'])->references(['id'])->on('sections')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['status_id'])->references(['id'])->on('statuses')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign('complaints_assigned_by_foreign');
            $table->dropForeign('complaints_assigned_to_foreign');
            $table->dropForeign('complaints_network_type_id_foreign');
            $table->dropForeign('complaints_request_type_id_foreign');
            $table->dropForeign('complaints_section_id_foreign');
            $table->dropForeign('complaints_status_id_foreign');
        });
    }
};
