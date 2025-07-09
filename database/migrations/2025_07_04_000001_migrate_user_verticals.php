<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Copy existing users.vertical_id into user_vertical pivot
        $users = DB::table('users')->whereNotNull('vertical_id')->get();
        foreach ($users as $user) {
            DB::table('user_vertical')->updateOrInsert([
                'user_id' => $user->id,
                'vertical_id' => $user->vertical_id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove migrated records
        $users = DB::table('users')->whereNotNull('vertical_id')->get();
        foreach ($users as $user) {
            DB::table('user_vertical')->where([
                'user_id' => $user->id,
                'vertical_id' => $user->vertical_id,
            ])->delete();
        }
    }
}; 