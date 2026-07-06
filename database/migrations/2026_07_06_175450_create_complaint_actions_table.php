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
        Schema::create('complaint_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('complaint_id')->index('complaint_actions_complaint_id_foreign');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('status_id')->index('complaint_actions_status_id_foreign');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_actions');
    }
};
