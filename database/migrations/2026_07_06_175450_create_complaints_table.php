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
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_number', 50)->unique();
            $table->string('user_name')->nullable();
            $table->unsignedBigInteger('client_id')->default(0);
            $table->unsignedBigInteger('network_type_id')->nullable()->index('complaints_network_type_id_foreign');
            $table->unsignedBigInteger('section_id')->nullable()->index('complaints_section_id_foreign');
            $table->string('intercom')->nullable();
            $table->string('room_number', 6)->nullable();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->unsignedBigInteger('request_type_id')->nullable()->index('complaints_request_type_id_foreign');
            $table->unsignedBigInteger('status_id')->index('complaints_status_id_foreign');
            $table->unsignedBigInteger('assigned_to')->nullable()->index('complaints_assigned_to_foreign');
            $table->unsignedBigInteger('assigned_by')->nullable()->index('complaints_assigned_by_foreign');
            $table->text('resolution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
