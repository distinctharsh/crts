<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Status;
use Carbon\Carbon;

class ComplaintAutoCloser
{
    public static function autoClose()
    {
        $completedStatus = Status::where('name', 'completed')->first();
        $closedStatus = Status::where('name', 'closed')->first();
        if (!$completedStatus || !$closedStatus) {
            return;
        }

        $sevenDaysAgo = Carbon::now()->subDays(7);

        Complaint::where('status_id', $completedStatus->id)
            ->where('updated_at', '<=', $sevenDaysAgo)
            ->update(['status_id' => $closedStatus->id]);
    }
} 