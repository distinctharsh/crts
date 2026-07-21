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
    public function up()
    {
        DB::statement("
            UPDATE complaints c
            JOIN complaint_vertical cv ON c.id = cv.complaint_id
            SET c.vertical_id = cv.vertical_id
        ");
    }

    public function down()
    {
        Schema::table('complaints', function (Blueprint $table) {
            //
        });
    }
};
