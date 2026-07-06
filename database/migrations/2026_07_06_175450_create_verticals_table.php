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
        Schema::create('verticals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('short_form', 10)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->index('verticals_parent_id_foreign')->comment('Null means top-level category');
            $table->boolean('send_email')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verticals');
    }
};
