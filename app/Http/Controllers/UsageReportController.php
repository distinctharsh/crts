<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Complaint;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsageReportController extends Controller
{
    public function index()
    {
        // Get all VMs and NFOs
        $users = User::whereHas('role', function($query) {
            $query->whereIn('slug', ['vm', 'nfo']);
        })->with(['role', 'assignedComplaints.status'])->get();

        $reportData = [];
        $completedStatusIds = Status::whereIn('name', ['completed', 'closed'])->pluck('id');
        $pendingStatusIds = Status::whereNotIn('name', ['completed', 'closed'])->pluck('id');

        foreach ($users as $user) {
            $assignedComplaints = $user->assignedComplaints;
            
            $completed = $assignedComplaints->whereIn('status_id', $completedStatusIds)->count();
            $pending = $assignedComplaints->whereIn('status_id', $pendingStatusIds)->count();
            $total = $assignedComplaints->count();

            $reportData[] = [
                'id' => $user->id,
                'name' => $user->full_name ?: $user->username,
                'role' => $user->role->name,
                'pending' => $pending,
                'completed' => $completed,
                'total' => $total,
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
            ];
        }

        // Sort by role (VM first) and then by name
        usort($reportData, function($a, $b) {
            if ($a['role'] === $b['role']) {
                return strcmp($a['name'], $b['name']);
            }
            return $a['role'] === 'VM' ? -1 : 1;
        });

        return view('usage-report.index', compact('reportData'));
    }
}
