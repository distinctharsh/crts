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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id')->nullable()->index('users_role_id_foreign');
            $table->string('username', 50)->unique();
            $table->string('email')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('full_name', 100);
            $table->unsignedBigInteger('vertical_id')->nullable()->index('users_vertical_id_foreign');
            $table->string('password');
            $table->boolean('must_change_password')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
