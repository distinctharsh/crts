<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add new status_id column (nullable for now)
        if (!Schema::hasColumn('complaint_actions', 'status_id')) {
            Schema::table('complaint_actions', function (Blueprint $table) {
                $table->unsignedBigInteger('status_id')->nullable()->after('assigned_to');
            });
        }

        // 2. Migrate data from action string to status_id
        $statusMap = [
            'assigned' => 2,
            'closed' => 6,
            'completed' => 8,
            'in_progress' => 3,
            'pending_with_user' => 5,
            'pending_with_vendor' => 4,
            'Red' => 12,
            'unassigned' => 1,
        ];
        foreach (DB::table('complaint_actions')->get() as $action) {
            $statusId = $statusMap[$action->action] ?? 1; // Default to 'unassigned' if not found
            DB::table('complaint_actions')->where('id', $action->id)->update(['status_id' => $statusId]);
        }

        // 3. Make status_id not nullable and drop action column
        Schema::table('complaint_actions', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->nullable(false)->change();
            if (Schema::hasColumn('complaint_actions', 'action')) {
                $table->dropColumn('action');
            }
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add action column back
        Schema::table('complaint_actions', function (Blueprint $table) {
            $table->string('action')->nullable()->after('assigned_to');
        });

        // 2. Migrate data from status_id to action string (reverse mapping)
        $idToName = [
            2 => 'assigned',
            6 => 'closed',
            8 => 'completed',
            3 => 'in_progress',
            5 => 'pending_with_user',
            4 => 'pending_with_vendor',
            12 => 'Red',
            1 => 'unassigned',
        ];
        foreach (DB::table('complaint_actions')->get() as $action) {
            $actionName = $idToName[$action->status_id] ?? null;
            if ($actionName) {
                DB::table('complaint_actions')->where('id', $action->id)->update(['action' => $actionName]);
            }
        }

        // 3. Drop status_id column
        Schema::table('complaint_actions', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
