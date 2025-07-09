<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->boolean('visible_to_user')->default(true);
        });
    }

    public function down()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropColumn('visible_to_user');
        });
    }
}; 