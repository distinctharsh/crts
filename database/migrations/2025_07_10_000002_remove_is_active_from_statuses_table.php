<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('statuses', function (Blueprint $table) {
            if (Schema::hasColumn('statuses', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    public function down()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }
}; 