<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ComplaintAutoCloser;

class DashboardController extends Controller
{
public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            $query = Complaint::query()->with([
                'client', 
                'assignedTo', 
                'networkType' => fn($q) => $q->withTrashed(), 
                'vertical' => fn($q) => $q->withTrashed(), 
                'status' => fn($q) => $q->withTrashed(), 
                'section' => fn($q) => $q->withTrashed(), 
                'requestType' => fn($q) => $q->withTrashed()
            ]);

            if ($user) {
                if ($user->isManager()) {
                    $activeStatusIds = Status::withTrashed()->whereIn('name', [
                        'unassigned', 'assigned', 'pending_with_vendor', 'pending_with_user', 'assign_to_me', 'completed', 'closed', 'in_progress'
                    ])->pluck('id');
                    $query->whereIn('status_id', $activeStatusIds);
                } elseif ($user->isVM()) {
                    $userVerticalIds = $user->verticals->pluck('id')->toArray();
                    $allSubVerticalIds = Vertical::withTrashed()->whereIn('parent_id', $userVerticalIds)->pluck('id')->toArray();
                    $allGrandChildIds = [];
                    if (!empty($allSubVerticalIds)) {
                        $allGrandChildIds = Vertical::withTrashed()->whereIn('parent_id', $allSubVerticalIds)->pluck('id')->toArray();
                    }
                    $allAllowedVerticalIds = array_unique(array_merge($userVerticalIds, $allSubVerticalIds, $allGrandChildIds));
                    $query->whereIn('vertical_id', $allAllowedVerticalIds);
                } elseif ($user->isNFO()) {
                    $query->where('assigned_to', $user->id);
                } else {
                    $query->where('client_id', $user->id);
                }
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }
            if ($request->filled('by')) {
                $searchByUserId = is_array($request->input('by')) ? $request->input('by') : explode(',', $request->input('by'));
                $query->whereIn('assigned_to', $searchByUserId);
            }
            if ($request->filled('status')) {
                $searchByStatus = is_array($request->input('status')) ? $request->input('status') : explode(',', $request->input('status'));
                $query->whereIn('status_id', $searchByStatus);
            }
            if ($request->filled('vertical_id')) {
                $searchByVerticalId = is_array($request->input('vertical_id')) ? $request->input('vertical_id') : explode(',', $request->input('vertical_id'));
                $allVerticalIdsToSearch = [];
                foreach ($searchByVerticalId as $vId) {
                    if (empty($vId)) continue;
                    $allVerticalIdsToSearch[] = (int) $vId;
                    $childIds = Vertical::withTrashed()->where('parent_id', $vId)->pluck('id')->toArray();
                    if (!empty($childIds)) {
                        $allVerticalIdsToSearch = array_merge($allVerticalIdsToSearch, $childIds);
                        $grandChildIds = Vertical::withTrashed()->whereIn('parent_id', $childIds)->pluck('id')->toArray();
                        if (!empty($grandChildIds)) {
                            $allVerticalIdsToSearch = array_merge($allVerticalIdsToSearch, $grandChildIds);
                        }
                    }
                }
                $allVerticalIdsToSearch = array_unique($allVerticalIdsToSearch);
                $query->whereIn('vertical_id', $allVerticalIdsToSearch);
            }
            if ($request->filled('networktype')) {
                $searchBynetworkType = is_array($request->input('networktype')) ? $request->input('networktype') : explode(',', $request->input('networktype'));
                $query->whereIn('network_type_id', $searchBynetworkType);
            }
            if ($request->filled('request_type_id')) {
                $searchByRequestType = is_array($request->input('request_type_id')) ? $request->input('request_type_id') : explode(',', $request->input('request_type_id'));
                $query->whereIn('request_type_id', $searchByRequestType);
            }
            if ($request->filled('section')) {
                $searchBySection = is_array($request->input('section')) ? $request->input('section') : explode(',', $request->input('section'));
                $query->whereIn('section_id', $searchBySection);
            }

            // Dates
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::createFromFormat('d/m/Y', $request->input('date_from'))->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            }
            if ($request->filled('date_to')) {
                $dateTo = Carbon::createFromFormat('d/m/Y', $request->input('date_to'))->endOfDay();
                $query->where('created_at', '<=', $dateTo);
            }

            // Quick Filter
            if ($request->filled('quick_filter')) {
                $val = strtolower($request->input('quick_filter'));
                $amount = (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
                $unit = preg_replace('/[0-9]/', '', $val);

                if ($amount > 0 && in_array($unit, ['h', 'd', 'w', 'm', 'y'])) {
                    $threshold = Carbon::now();
                    switch ($unit) {
                        case 'h': $threshold->subHours($amount); break;
                        case 'd': $threshold->subDays($amount); break;
                        case 'w': $threshold->subWeeks($amount); break;
                        case 'm': $threshold->subMonths($amount); break;
                        case 'y': $threshold->subYears($amount); break;
                    }

                    $query->where('created_at', '<=', $threshold);

                    $doneStatusIds = Status::withTrashed()->whereIn('name', ['completed', 'closed'])->pluck('id');
                    if ($doneStatusIds->isNotEmpty()) {
                        $query->whereNotIn('status_id', $doneStatusIds);
                    }
                }
            }

            if (request('assigned_to_me') == '1') {
                $query->where('assigned_to', $user->id);
                $excludedStatuses = Status::withTrashed()->whereIn('name', ['closed', 'completed'])->pluck('id');
                if ($excludedStatuses->isNotEmpty()) {
                    $query->whereNotIn('status_id', $excludedStatuses);
                }
            }
            if ($request->filled('unassigned') && $request->input('unassigned') == '1') {
                $query->whereNull('assigned_to');
            }

            // Handle Pagination & "All"
            $perPage = $request->input('per_page', 10);

            if ($perPage === 'all') {
                $allResults = $query->latest()->get();
                $total = $allResults->count();
                
                $complaints = new LengthAwarePaginator(
                    $allResults,
                    $total > 0 ? $total : 1,
                    $total > 0 ? $total : 1,
                    1,
                    ['path' => $request->url(), 'query' => $request->query()]
                );
            } else {
                $complaints = $query->latest()->paginate((int)$perPage)->withQueryString();
            }

            $managers = User::withTrashed()->whereHas('role', function ($q) {
                $q->where('slug', 'manager');
            })->get();

            $statuses = Status::withTrashed()->ordered()->where('name', '!=', 'assign_to_me')->get();

            $verticals = Vertical::withTrashed()->with(['parent' => fn($q) => $q->withTrashed()])->get()->sortBy(function($vertical) {
                return $vertical->full_path ?? $vertical->name;
            });

            $usersList = User::withTrashed()->select('id', 'full_name')->orderBy('full_name')->get();
            $networkTypes = NetworkType::withTrashed()->get();
            $sections = Section::withTrashed()->get();
            $closeStatus = Status::withTrashed()->where('name', 'closed')->first();
            $requestTypes = RequestType::withTrashed()->get();

            return view('complaints.index', compact('complaints', 'usersList', 'managers', 'statuses', 'networkTypes', 'sections', 'verticals', 'closeStatus', 'requestTypes'));
        } catch (\Exception $e) {
            \Log::error('Complaint index error: ' . $e->getMessage());
            return redirect('/home')->with('error', 'Something went wrong while loading complaints. Please try again.');
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
