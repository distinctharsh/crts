<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Complaint;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsageReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from') ? Carbon::parse($request->input('date_from'))->startOfDay() : null;
        $dateTo = $request->input('date_to') ? Carbon::parse($request->input('date_to'))->endOfDay() : null;

        // Get all VMs and NFOs
        $users = User::whereHas('role', function($query) {
            $query->whereIn('slug', ['vm', 'nfo']);
        })->with(['role'])->get();

        $reportData = [];
        $completedStatusIds = Status::whereIn('name', ['completed', 'closed'])->pluck('id');
        $pendingStatusIds = Status::whereNotIn('name', ['completed', 'closed'])->pluck('id');

        foreach ($users as $user) {
            $query = $user->assignedComplaints();
            
            // Apply date range filter if provided
            if ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->where('created_at', '<=', $dateTo);
            }
            
            $assignedComplaints = $query->get();
            
            $completed = $assignedComplaints->whereIn('status_id', $completedStatusIds)->count();
            $pending = $assignedComplaints->whereIn('status_id', $pendingStatusIds)->count();
            $total = $assignedComplaints->count();

            $reportData[] = [
                'id' => $user->id,
                'name' => $user->full_name ?: $user->username,
                'pending' => $pending,
                'completed' => $completed,
                'total' => $total,
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
            ];
        }

        return view('usage-report.index', [
            'reportData' => $reportData,
            'dateFrom' => $request->input('date_from'),
            'dateTo' => $request->input('date_to')
        ]);
    }
}
