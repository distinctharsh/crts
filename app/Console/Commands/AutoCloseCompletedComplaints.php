<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Complaint;
use App\Models\Status;
use Carbon\Carbon;

class AutoCloseCompletedComplaints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'complaints:auto-close-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close complaints that have been completed for more than 7 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $completedStatus = Status::where('name', 'completed')->first();
        $closedStatus = Status::where('name', 'closed')->first();
        if (!$completedStatus || !$closedStatus) {
            $this->error('Required statuses not found.');
            return 1;
        }

        $sevenDaysAgo = Carbon::now()->subDays(7);

        $complaints = Complaint::where('status_id', $completedStatus->id)
            ->where('updated_at', '<=', $sevenDaysAgo)
            ->get();

        $count = 0;
        foreach ($complaints as $complaint) {
            $complaint->status_id = $closedStatus->id;
            $complaint->save();
            $count++;
        }

        $this->info("Closed {$count} complaints that were completed for more than 7 days.");
        return 0;
    }
} 