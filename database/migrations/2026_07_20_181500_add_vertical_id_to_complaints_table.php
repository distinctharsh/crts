<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->unsignedBigInteger('vertical_id')->nullable()->after('request_type_id')->index('complaints_vertical_id_foreign');
            $table->foreign(['vertical_id'])->references(['id'])->on('verticals')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (Schema::hasColumn('complaints', 'vertical_id')) {
                $table->dropForeign('complaints_vertical_id_foreign');
                $table->dropColumn('vertical_id');
            }
        });
    }
};
