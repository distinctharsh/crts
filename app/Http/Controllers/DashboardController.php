<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Status;
use App\Models\Vertical;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        try {
            $user = auth()->user();
            $baseQuery = Complaint::query();

            if ($user) {
                if ($user->isManager()) {
                    $activeStatusIds = Status::whereIn('name', [
                        'unassigned', 'assigned', 'pending_with_vendor', 'pending_with_user', 'assign_to_me', 'completed', 'closed', 'in_progress'
                    ])->pluck('id');

                    $baseQuery->whereIn('status_id', $activeStatusIds);
                } 
                
                elseif ($user->isVM()) {
                    $userVerticalIds = $user->verticals->pluck('id')->toArray();
                    $allSubVerticalIds = \App\Models\Vertical::whereIn('parent_id', $userVerticalIds)->pluck('id')->toArray();
                    $allGrandChildIds = [];
                    if (!empty($allSubVerticalIds)) {
                        $allGrandChildIds = \App\Models\Vertical::whereIn('parent_id', $allSubVerticalIds)->pluck('id')->toArray();
                    }
                    $allAllowedVerticalIds = array_unique(array_merge($userVerticalIds, $allSubVerticalIds, $allGrandChildIds));
                    $baseQuery->whereIn('vertical_id', $allAllowedVerticalIds);
                }   
                elseif ($user->isNFO()) {
                    $baseQuery->where('assigned_to', $user->id);
                } 
                
                else {
                    $baseQuery->where('client_id', $user->id);
                }
            }

            $statusIds = Status::whereIn('name', [
                'unassigned',
                'assigned',
                'pending_with_vendor',
                'pending_with_user',
                'assign_to_me',
                'completed',
                'closed',
                'in_progress'
            ])->pluck('id', 'name');

            $completedStatusId = $statusIds->get('completed');
            $closedStatusId = $statusIds->get('closed');

            $baseQuery->where(function ($query) use ($completedStatusId, $closedStatusId) {
                $query->whereDate('created_at', today())
                    ->orWhere(function ($query) use ($completedStatusId, $closedStatusId) {
                        $query->whereDate('created_at', '<', today())
                            ->whereNotIn('status_id', [
                                $completedStatusId,
                                $closedStatusId
                            ]);
                    });
            });

            $complaints = (clone $baseQuery)
                ->with(['client', 'networkType', 'vertical', 'status', 'assignedTo'])
                ->latest()
                ->get();

            $todayComplaints = $complaints->filter(function ($complaint) {
                return $complaint->created_at->isToday();
            });

            $previousComplaints = $complaints->filter(function ($complaint) {
                return !$complaint->created_at->isToday();
            });

            $managers = \App\Models\User::whereHas('role', function($q) {
                $q->where('slug', 'manager');
            })->get();

            $closeStatus = Status::where('name', 'closed')->first();
            $assignableUsers = $user ? $user->getAssignableUsers() : collect();

            $data = [
                'totalComplaints' => (clone $baseQuery)->count(),
                'unassignedComplaints' => (clone $baseQuery)->where('status_id', $statusIds->get('unassigned'))->count(),
                'assignedComplaints' => (clone $baseQuery)->where('status_id', $statusIds->get('assigned'))->count(),
                'pendingWithVendorComplaints' => (clone $baseQuery)->where('status_id', $statusIds->get('pending_with_vendor'))->count(),
                'pendingWithUserComplaints' => (clone $baseQuery)->where('status_id', $statusIds->get('pending_with_user'))->count(),
                'assignToMeComplaints' => (clone $baseQuery)
                    ->where('assigned_to', $user->id)
                    ->whereNotIn('status_id', [
                        $statusIds->get('closed'),
                        $statusIds->get('completed')
                    ])
                    ->count(),

                'completedComplaints' => (clone $baseQuery)->where('status_id', $statusIds->get('completed'))->count(),
                'closedComplaints' => (clone $baseQuery)->where('status_id', $statusIds->get('closed'))->count(),
                'todayComplaints' => $todayComplaints,
                'previousComplaints' => $previousComplaints,
                'unassignedStatusId' => $statusIds->get('unassigned'),
                'completedStatusId' => $completedStatusId,
                'closedStatusId' => $closedStatusId,
                'assignToMeStatusId' => null,
                'managers' => $managers,
                'closeStatus' => $closeStatus,
                'assignableUsers' => $assignableUsers,
            ];

            return view('dashboard', $data)->with('error', null);

        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return view('dashboard', [
                'totalComplaints' => 0,
                'unassignedComplaints' => 0,
                'assignedComplaints' => 0,
                'pendingWithVendorComplaints' => 0,
                'pendingWithUserComplaints' => 0,
                'assignToMeComplaints' => 0,
                'completedComplaints' => 0,
                'closedComplaints' => 0,
                'todayComplaints' => collect(),
                'previousComplaints' => collect(),
                'unassignedStatusId' => null,
                'completedStatusId' => null,
                'closedStatusId' => null,
                'assignToMeStatusId' => null,
                'managers' => collect(),
                'closeStatus' => null,
                'assignableUsers' => collect(),
            ])->with('error', 'There was an error loading the dashboard. Please try again.');
        }
    }

    public function getStatusColorAttribute()
    {
        return [
            'unassigned' => 'warning',
            'assigned' => 'info',
            'pending_with_vendor' => 'primary',
            'pending_with_user' => 'primary',
            'assign_to_me' => 'info',
            'completed' => 'success',
            'closed' => 'secondary',
        ][$this->status] ?? 'secondary';
    }

    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'info',
            'medium' => 'warning',
            'high' => 'danger'
        ][$this->priority] ?? 'secondary';
    }
}
