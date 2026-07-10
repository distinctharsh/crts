<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Models\ComplaintAction;
use App\Models\NetworkType;
use App\Models\Section;
use App\Models\Vertical;
use App\Models\SubCategory;
use App\Models\RequestType;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\ComplaintNotificationService;
use App\Mail\HODReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'show', 'history', 'track', 'lookup', 'live', 'liveData', 'intercomSuggestions', 'bulkImport', 'bulkImportStore', 'downloadFormat', 'notificationData']);
    }

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Complaint::query()->with(['client', 'assignedTo', 'networkType', 'verticals', 'status', 'section']);
            if ($user) {
                if ($user->isManager()) {
                    $activeStatusIds = Status::whereIn('name', [
                        'unassigned', 'assigned', 'pending_with_vendor', 'pending_with_user', 'assign_to_me', 'completed', 'closed', 'in_progress'
                    ])->pluck('id');
                    $query->whereIn('status_id', $activeStatusIds);
                } elseif ($user->isVM()) {
                    $verticalIds = $user->verticals->pluck('id');
                    $query->whereHas('verticals', function($q) use ($verticalIds) {
                        $q->whereIn('verticals.id', $verticalIds);
                    });
                } elseif ($user->isNFO()) {
                    $query->where('assigned_to', $user->id);
                } else {
                    $query->where('client_id', $user->id);
                }
            }
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }
            if ($request->filled('by')) {
                $searchByUserId = $request->input('by');
                $query->whereHas('assignedTo', function ($q) use ($searchByUserId) {
                    $q->where('id', $searchByUserId);
                });
            }
            if ($request->filled('status')) {
                $searchByStatus = (array) $request->input('status');
                $query->whereIn('status_id', $searchByStatus);
            }
            if ($request->filled('vertical')) {
                $searchByVertical = (array) $request->input('vertical');
                $query->whereHas('verticals', function($q) use ($searchByVertical) {
                    $q->whereIn('verticals.id', $searchByVertical);
                });
            }
            if ($request->filled('networktype')) {
                $searchBynetworkType = (array) $request->input('networktype');
                $query->whereIn('network_type_id', $searchBynetworkType);
            }
            if ($request->filled('section')) {
                $searchBySection = (array) $request->input('section');
                $query->whereIn('section_id', $searchBySection);
            }
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            }
            if ($request->filled('date_to')) {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                $query->where('created_at', '<=', $dateTo);
            }
            if (request('assigned_to_me') == '1') {
                $query->where('assigned_to', $user->id);
                $excludedStatuses = Status::whereIn('name', ['closed', 'completed'])->pluck('id');
                if ($excludedStatuses->isNotEmpty()) {
                    $query->whereNotIn('status_id', $excludedStatuses);
                }
            }
            if ($request->filled('unassigned') && $request->input('unassigned') == '1') {
                $query->whereNull('assigned_to');
            }
            $managers = User::whereHas('role', function ($q) {
                $q->where('slug', 'manager');
            })->get();
            $statuses = Status::query()->ordered()->where('name', '!=', 'assign_to_me')->get();
            $complaints = $query->latest()->get();
            foreach ($complaints as $complaint) {
                $complaint->assignableUsers = $user->getAssignableUsers($complaint);
            }
            $usersList = User::with('role')
                ->select('users.id', 'users.full_name')
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->orderByRaw("
                    CASE 
                        WHEN roles.slug = 'manager' THEN 0
                        WHEN roles.slug = 'vm' THEN 1
                        WHEN roles.slug = 'nfo' THEN 2
                        ELSE 3
                    END
                ")
                ->orderBy('users.full_name')
                ->get();
            $verticals = Vertical::get();
            $networkTypes = NetworkType::get();
            $sections = Section::get();
            return view('complaints.index', compact('complaints', 'usersList', 'managers', 'statuses', 'networkTypes', 'sections', 'verticals'));
        } catch (\Exception $e) {
            \Log::error('Complaint index error: ' . $e->getMessage());
            return redirect('/home')->with('error', 'Something went wrong while loading complaints. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)');
        }
    }


    public function create()
    {
        $networkTypes = NetworkType::all();
        $verticals = Vertical::whereNull('parent_id')->get();
        $sections = Section::all();
        $requestTypes = RequestType::all();
        $intercoms = Complaint::whereNotNull('intercom')
        ->distinct()
        ->pluck('intercom');
        
        return view('complaints.create', compact('networkTypes', 'verticals', 'sections', 'intercoms', 'requestTypes'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'network_type_id' => 'required|exists:network_types,id',
                'request_type_id' => 'required|exists:request_types,id',
                'priority' => 'nullable|in:high',
                'description' => 'required|string',
                'vertical_ids' => 'required|array|min:1',
                'vertical_ids.*' => 'exists:verticals,id',
                'user_name' => 'required|string|max:255',
                'file' => 'nullable|file|max:2048',
                'section_id' => 'required|exists:sections,id',
                'intercom' => 'required|string|max:255',
                'room_number' => 'required|integer',
                'assigned_to' => 'nullable|exists:users,id',
            ]);

            $priority = $validated['priority'] ?? 'medium';

            $unassignedStatus = Status::where('name', 'unassigned')->first();

            $date = Carbon::now()->format('Ymd');
            
            $verticalsChain = Vertical::whereIn('id', $validated['vertical_ids'])
                ->orderByRaw('FIELD(id, ' . implode(',', $validated['vertical_ids']) . ')')
                ->get();

            $prefixParts = [];
            foreach ($verticalsChain as $v) {
                if ($v->short_form) {
                    $prefixParts[] = strtoupper($v->short_form);
                }
            }
            $prefix = !empty($prefixParts) ? implode('-', $prefixParts) : 'CMP';
            
            $complaintsToday = Complaint::whereDate('created_at', Carbon::today())->count();
            $referenceNumber = $prefix . '-' . $date . str_pad($complaintsToday + 1, 3, '0', STR_PAD_LEFT);

            $assignedStatus = Status::where('name','assigned')->first();
            $statusId = $request->filled('assigned_to')
                ? $assignedStatus->id
                : $unassignedStatus->id;

            $complaint = Complaint::create([
                'reference_number' => $referenceNumber,
                'client_id' => Auth::user()->id ?? 0,
                'description' => $validated['description'],
                'priority' => $priority,
                'status_id' => $statusId,
                'network_type_id' => $validated['network_type_id'],
                'request_type_id' => $validated['request_type_id'],
                'section_id' => $validated['section_id'],
                'user_name' => $validated['user_name'],
                'room_number' => $validated['room_number'],
                'file_path' => $request->hasFile('file') ? $request->file('file')->store('complaint_files', 'public') : null,
                'intercom' => $validated['intercom'],
                'assigned_to' => $request->assigned_to ?: null,
                'created_at' => Carbon::now()->setTimezone(config('app.timezone')),
                'updated_at' => Carbon::now()->setTimezone(config('app.timezone')),
            ]);

            $complaint->verticals()->sync($validated['vertical_ids']);

            $complaint->load('verticals');

            ComplaintAction::create([
                'complaint_id' => $complaint->id,
                'user_id' => Auth::user()->id ?? 0,
                'status_id' => $unassignedStatus->id,
                'description' => 'Complaint created',
                'assigned_to' => $complaint->assigned_to ?: null,
                'changes' => json_encode([
                    ...$complaint->getChanges(),
                    'verticals' => $complaint->verticals->pluck('name')->toArray()
                ])
            ]);

            try {
                $notificationService = new ComplaintNotificationService();
                $notificationService->sendNewComplaintNotifications($complaint);
            } catch (\Exception $e) {
                \Log::error('Email notification failed: ' . $e->getMessage());
            }

            return redirect()->route('complaints.show', $complaint)
                ->with('success', 'Complaint created successfully.');
        } catch (\Exception $e) {
            \Log::error('Complaint Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Complaint $complaint)
    {
        try {
            $this->authorize('update', $complaint);

            $networkTypes = NetworkType::all();
            $verticals = Vertical::whereNull('parent_id')->get();
            $sections = Section::all();
            $requestTypes = RequestType::all();
            $statuses = Status::query()->ordered()->get();
            $intercoms = Complaint::whereNotNull('intercom')->distinct()->pluck('intercom');
            $complaint->load(['client', 'assignedTo.role', 'status', 'verticals']);
            $savedVerticals = $complaint->verticals->pluck('id')->toArray();
            $assignedUser = $complaint->assignedTo;

            return view('complaints.create', compact('complaint', 'networkTypes', 'verticals', 'sections', 'statuses', 'intercoms', 'savedVerticals', 'assignedUser', 'requestTypes'));
        } catch (\Exception $e) {
            \Log::error('Complaint edit error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while editing complaint.');
        }
    }

    public function update(Request $request, Complaint $complaint)
    {
        try {
            $this->authorize('update', $complaint);
            if ($request->has('status_id') && $request->has('description') && count($request->all()) <= 5) {
                $validated = $request->validate([
                    'status_id' => 'required|exists:statuses,id',
                    'description' => 'nullable|string',
                ]);
                
                $complaint->update(['status_id' => $validated['status_id']]);

                ComplaintAction::create([
                    'complaint_id' => $complaint->id,
                    'user_id' => Auth::user()->id ?? 0,
                    'status_id' => $validated['status_id'],
                    'description' => $validated['description'] ?? 'checked',
                ]);

                return redirect()->route('complaints.index')->with('success', 'Complaint closed successfully.');
            }

            $validated = $request->validate([
                'network_type_id' => 'required|exists:network_types,id',
                'request_type_id' => 'required|exists:request_types,id',
                'description' => 'required|string',
                'vertical_ids' => 'required|array|min:1',
                'vertical_ids.*' => 'exists:verticals,id',
                'user_name' => 'required|string|max:255',
                'section_id' => 'required|exists:sections,id',
                'intercom' => 'required|string|max:255',
                'room_number' => 'required|numeric|min:0|max:999999',
                'priority' => 'nullable|in:high',
                'status_id' => 'required|exists:statuses,id',
                'file' => 'nullable|file|max:2048',
                'delete_file' => 'nullable|boolean',
                'assigned_to' => 'nullable|exists:users,id',
            ]);

            $validated['priority'] = $validated['priority'] ?? 'medium';

            if ($request->boolean('delete_file') && $complaint->file_path) {
                Storage::disk('public')->delete($complaint->file_path);
                $complaint->file_path = null;
            }

            if ($request->hasFile('file')) {
                if ($complaint->file_path) {
                    Storage::disk('public')->delete($complaint->file_path);
                }
                $complaint->file_path = $request->file('file')->store('complaint_files', 'public');
            }

            $oldAssignedTo = $complaint->assigned_to;
            $oldStatusId = $complaint->status_id;
            $oldRequestTypeId = $complaint->request_type_id;

            if (array_key_exists('assigned_to', $validated) && $validated['assigned_to'] != $oldAssignedTo) {
                $complaint->assigned_by = Auth::user()->id ?? 0;
            }

            $verticalIds = $validated['vertical_ids'];

            $oldVerticals = $complaint->verticals->pluck('name')->toArray();

            $complaint->user_name = $validated['user_name'];
            $complaint->network_type_id = $validated['network_type_id'];
            $complaint->request_type_id = $validated['request_type_id'];
            $complaint->description = $validated['description'];
            $complaint->section_id = $validated['section_id'];
            $complaint->intercom = $validated['intercom'];
            $complaint->room_number = $validated['room_number'];
            $complaint->priority = $validated['priority'];
            $complaint->status_id = $validated['status_id'];
            if(isset($validated['assigned_to'])) {
                $complaint->assigned_to = $validated['assigned_to'];
            }
            $complaint->save();

            $complaint->verticals()->sync($verticalIds);

            $complaint->load('verticals');
            $newVerticals = $complaint->verticals->pluck('name')->toArray();

            $changes = [];
            if ($oldStatusId != $complaint->status_id) {
                $changes['status'] = ['old' => $oldStatusId, 'new' => $complaint->status_id];
            }
            if ($oldAssignedTo != $complaint->assigned_to) {
                $changes['assigned_to'] = ['old' => $oldAssignedTo, 'new' => $complaint->assigned_to];
            }
            if ($oldVerticals != $newVerticals) {
                $changes['verticals'] = ['old' => implode(', ', $oldVerticals), 'new' => implode(', ', $newVerticals)];
            }
            if ($oldRequestTypeId != $complaint->request_type_id) {
                $changes['request_type'] = ['old' => $oldRequestTypeId, 'new' => $complaint->request_type_id];
            }

            ComplaintAction::create([
                'complaint_id' => $complaint->id,
                'user_id' => Auth::user()->id ?? 0,
                'status_id' => $complaint->status_id,
                'assigned_to' => $complaint->assigned_to ?: null,
                'description' => 'Complaint updated',
                'changes' => json_encode($changes)
            ]);

            if ($oldAssignedTo != $complaint->assigned_to && $complaint->assigned_to) {
                try {
                    $notificationService = new ComplaintNotificationService();
                    $notificationService->sendAssignedComplaintNotifications($complaint);
                } catch (\Exception $e) {
                    \Log::error('Email notification failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('complaints.index')->with('success', 'Complaint updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Complaint update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while updating complaint. Please try again.');
        }
    }



    public function assign(Request $request, Complaint $complaint)
    {
        try {
            $user = Auth::user();

            if (!$user->isManager() && !$user->isVM() && !$user->isNFO()) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'assigned_to' => 'required|exists:users,id',
                'description' => 'nullable|string'
            ]);

            $assignee = User::findOrFail($validated['assigned_to']);

            // Check if the assignee has the correct role based on the current user's role
            if ($user->isManager()) {
                if (!$assignee->isVM() && !$assignee->isNFO()) {
                    abort(403, 'Managers can only assign to VMs or NFOs.');
                }
            } elseif ($user->isVM()) {
                if (!$assignee->isNFO() && $assignee->id !== $user->id) {
                    abort(403, 'VMs can only self-assign or assign to NFOs.');
                }
            } elseif ($user->isNFO()) {
                if (!$assignee->isNFO() && !$assignee->isVM()) {
                    abort(403, 'NFOs can only assign to other NFOs or VMs.');
                }
            }

            // Get assigned status
            $assignedStatus = Status::where('name', 'assigned')->first();

            $complaint->update([
                'assigned_to' => $validated['assigned_to'],
                'assigned_by' => Auth::user()->id ?? 0,
                'status_id' => $assignedStatus->id
            ]);

            // Create action record
            ComplaintAction::create([
                'complaint_id' => $complaint->id,
                'user_id' => $user->id,
                'assigned_to' => $validated['assigned_to'], // <-- add this line
                'status_id' => $assignedStatus->id,
                'description' => $validated['description']
            ]);

            // Send email notification to assigned user with managers and VMs in CC
            try {
                $notificationService = new ComplaintNotificationService();
                $notificationService->sendAssignedComplaintNotifications($complaint);
            } catch (\Exception $e) {
                \Log::error('Email notification failed: ' . $e->getMessage());
                // Continue with redirect even if email fails
            }

            $previousUrl = url()->previous();
            $dashboardUrl = route('dashboard');

            if ($previousUrl === $dashboardUrl) {
                return redirect()->route('dashboard')->with('success', 'Complaint updated successfully.');
            }

            return redirect()->back()->with('success', 'Complaint updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Complaint assign error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while assigning complaint. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)');
        }
    }

    public function resolve(Request $request, Complaint $complaint)
    {
        try {
            $user = Auth::user();

            if (!$user->isNFO()) {
                abort(403, 'Only NFOs can resolve complaints.');
            }

            if ($complaint->assigned_to !== $user->id) {
                abort(403, 'You can only resolve complaints assigned to you.');
            }

            $validated = $request->validate([
                'description' => 'required|string',
                'status_id' => 'nullable|exists:statuses,id',
                'mark_closed' => 'nullable|boolean'
            ]);

            // Determine final status
            if ($request->has('mark_closed')) {
                $finalStatus = Status::where('name', 'closed')->first();
            } else {
                $finalStatus = Status::find($validated['status_id']);
            }

            $complaint->update([
                'status_id' => $finalStatus->id,
                'resolution' => $validated['description'], // ✅ always store what user typed
            ]);

            ComplaintAction::create([
                'complaint_id' => $complaint->id,
                'user_id' => $user->id,
                'status_id' => $finalStatus->id,
                'description' => $validated['description']
            ]);

            return redirect()->route('complaints.show', $complaint)
                ->with('success', 'Complaint ' . $finalStatus->name . ' successfully.');
        } catch (\Exception $e) {
            \Log::error('Complaint resolve error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while resolving complaint. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)');
        }
    }


    public function revert(Request $request, Complaint $complaint)
    {
        try {
            $user = Auth::user();

            if (!$user->isVM()) {
                abort(403, 'Only VMs can revert complaints to managers.');
            }

            if ($complaint->assigned_to !== $user->id) {
                abort(403, 'You can only revert complaints assigned to you.');
            }

            $validated = $request->validate([
                'assigned_to' => 'required|exists:users,id',
                'description' => 'nullable|string'
            ]);

            // Get reverted status
            $revertedStatus = Status::where('name', 'assigned')->first();

            $complaint->update([
                'assigned_to' => $validated['assigned_to'],
                'status_id' => $revertedStatus->id,
                'assigned_by' => $user->id
            ]);

            // Create action record
            ComplaintAction::create([
                'complaint_id' => $complaint->id,
                'user_id' => $user->id,
                'assigned_to' => $validated['assigned_to'],
                'status_id' => $revertedStatus->id,
                'description' => $validated['description']
            ]);

            return redirect()->route('complaints.show', $complaint)
                ->with('success', 'Complaint reverted to manager successfully.');
        } catch (\Exception $e) {
            \Log::error('Complaint revert error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while reverting complaint. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)');
        }
    }

    public function getAssignableUsers(Request $request)
    {
        try {
            $user = Auth::user();
            $complaint = null;
            $verticalIds = null;

            if ($request->has('complaint_id')) {
                $complaint = Complaint::find($request->complaint_id);
            }

            if ($request->has('vertical_ids')) {
                $verticalIds = $request->input('vertical_ids');
                // Handle both array and comma-separated string
                if (is_string($verticalIds)) {
                    $verticalIds = explode(',', $verticalIds);
                }
            }

            $assignableUsers = $user->getAssignableUsers($complaint, $verticalIds);

            return response()->json($assignableUsers);
        } catch (\Exception $e) {
            \Log::error('Complaint getAssignableUsers error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while fetching assignable users. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)'], 500);
        }
    }

    public function history(Request $request)
    {
        try {
            $query = \App\Models\Complaint::with(['actions' => function ($q) {
                $q->latest()->limit(1);
            }, 'actions.user']);

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('reference_number', 'like', "%$search%");
            }

            // Filter by action
            if ($request->filled('action')) {
                $query->whereHas('actions.status', function ($q) use ($request) {
                    $q->where('name', $request->action);
                });
            }

            // Filter by user
            if ($request->filled('by')) {
                $query->whereHas('actions.user', function ($q) use ($request) {
                    $q->where('username', $request->by);
                });
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereHas('actions', function ($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->date_from);
                });
            }
            if ($request->filled('date_to')) {
                $query->whereHas('actions', function ($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->date_to);
                });
            }

            $complaints = $query->latest()->paginate(10)->withQueryString();

            // For filter dropdowns
            $actionsList = \App\Models\Status::pluck('name');
            $usersList = \App\Models\User::select('username')->distinct()->pluck('username');

            return view('complaints.history', compact('complaints', 'actionsList', 'usersList'));
        } catch (\Exception $e) {
            \Log::error('Complaint history error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while loading complaint history. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)');
        }
    }



    public function show($id)
    {
        try {
            $complaint = \App\Models\Complaint::with(['client', 'assignedTo', 'actions.user', 'networkType', 'verticals', 'section', 'status', 'requestType'])->find($id);
            if (!$complaint) {
                return redirect('/home')->with('error', 'The complaint you are looking for does not exist.');
            }

            // Statuses for assigned user to update
            $statusOptions = \App\Models\Status::where('visible_to_user', true)
                ->ordered()
                ->get();

            // Show close/assign for manager (or VM if assigned to NFO) when status is completed
            $showCloseOrAssign = false;
            $user = Auth::user();
            if ($complaint->isCompleted()) {
                if (($user && $user->isManager()) || ($user && $user->isVM() && $complaint->assignedTo && $complaint->assignedTo->isNFO())) {
                    $showCloseOrAssign = true;
                }
            }
            $closeStatus = \App\Models\Status::where('name', 'closed')->first();

            return view('complaints.show', compact('complaint', 'statusOptions', 'showCloseOrAssign', 'closeStatus'));
        } catch (\Exception $e) {
            \Log::error('Complaint show error: ' . $e->getMessage());
            return redirect('/home')->with('error', 'The complaint you are looking for does not exist.');
        }
    }

    public function comment(Request $request, Complaint $complaint)
    {
        try {
            $isManager = Auth::user() && Auth::user()->isManager();
            $request->validate([
                'comment' => ($isManager ? 'required' : 'nullable') . '|string|max:2000',
                'status_id' => 'nullable|exists:statuses,id',
            ]);

            // Add comment only if not blank
            if (trim($request->comment ?? '') !== '') {
                $complaint->comments()->create([
                    'user_id' => Auth::user()->id ?? 0,
                    'comment' => $request->comment,
                ]);
            }

            // If status_id is present and different, update status and add to history
            if ($request->filled('status_id') && $complaint->status_id != $request->status_id) {
                $oldStatus = $complaint->status_id;
                $complaint->update(['status_id' => $request->status_id]);

                // Create action/history
                \App\Models\ComplaintAction::create([
                    'complaint_id' => $complaint->id,
                    'user_id' => Auth::user()->id ?? 0,
                    'status_id' => $request->status_id,
                    'description' => $request->comment,
                ]);
            }

            return redirect()->back()->with('success', 'Comment added successfully.');
        } catch (\Exception $e) {
            \Log::error('Complaint comment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while adding comment. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)');
        }
    }

    /**
     * Track a complaint by reference number (for guests).
     */
    public function track(Request $request)
    {
        try {
            $ref = $request->input('reference_number');
            $complaint = \App\Models\Complaint::where('reference_number', $ref)->first();
            if ($complaint) {
                return redirect()->route('complaints.show', $complaint->id);
            } else {
                return back()->with('error', 'Complaint not found');
            }
        } catch (\Exception $e) {
            \Log::error('Complaint track error: ' . $e->getMessage());
            return back()->with('error', 'Complaint not found');
        }
    }

    /**
     * AJAX lookup for complaint by reference number.
     */
    public function lookup(Request $request)
    {
        try {
            $ref = $request->input('reference_number');
            $complaint = \App\Models\Complaint::with(['client', 'networkType', 'verticals', 'status'])
                ->whereRaw('LOWER(reference_number) = ?', [strtolower($ref)])
                ->first();
            if ($complaint) {
                return response()->json([
                    'success' => true,
                    'complaint' => [
                        'reference_number' => $complaint->reference_number,
                        'status' => $complaint->status?->display_name ?? 'Unknown',
                        'status_color' => $complaint->status_color,
                        'priority' => ucfirst($complaint->priority),
                        'priority_color' => $complaint->priority_color,
                        'assigned_to' => $complaint->assignedTo->full_name ?? '' ,
                        'created_by' => $complaint->client?->full_name ?? $complaint->user_name ?? 'Guest',
                        'created_at' => $complaint->created_at->format('M d, Y H:i'),
                        'description' => $complaint->description,
                        'network' => $complaint->networkType?->name ?? 'N/A',
                        'verticals' => $complaint->verticals->pluck('name')->map(fn($name) => ucfirst($name))->implode(', ') ?? 'N/A',
                        'intercom' => $complaint->intercom ?? 'N/A',
                        'section' => $complaint->section?->name ?? 'N/A',
                    ]
                ]);
            } else {
                return response()->json(['success' => false, 'error' => 'Complaint not found.'], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Complaint lookup error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while looking up complaint. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)'], 500);
        }
    }

    /**
     * Show the live complaints dashboard (TV/room display)
     */
    public function live()
    {
        try {
            return view('complaints.live');
        } catch (\Exception $e) {
            \Log::error('Complaint live error: ' . $e->getMessage());
            return redirect('/home')->with('error', 'Something went wrong while loading live complaints. Please try again. (कुछ गलत हो गया, कृपया फिर से कोशिश करें.)');
        }
    }

    /**
     * Return JSON data for live complaints dashboard (for polling)
     */
    public function liveData()
    {
        try {
            $statuses = Status::whereIn('name', ['unassigned', 'assigned', 'pending_with_vendor', 'pending_with_user', 'assign_to_me', 'in_progress'])
                ->pluck('id');
            $startOfDay = now()->startOfDay();
            $endOfDay = now()->endOfDay();
            $complaints = Complaint::with(['assignedTo', 'status', 'networkType', 'requestType', 'section'])
                ->whereIn('status_id', $statuses)
                ->whereBetween('created_at', [
                    $startOfDay,
                    $endOfDay
                ])
                ->orderByDesc('created_at')
                ->get();

            $data = $complaints->map(function ($c) {
                return [
                    'id' => $c->id,
                    'reference_number' => $c->reference_number,
                    'user_name' => $c->user_name,
                    'room_number' => $c->room_number ?? 'N/A',
                    'intercom' => $c->intercom ?? 'N/A',
                    'network_type' => $c->networkType?->name ?? 'N/A', 
                    'request_type' => $c->requestType?->name ?? 'N/A',
                    'section' => $c->section?->name ?? 'N/A',
                    'status' => $c->status?->display_name ?? 'Unknown',
                    'priority' => ucfirst($c->priority),
                    'assigned_to' => $c->assigned_to,
                    'assigned_to_name' => $c->assignedTo?->full_name ?? null,
                    'description' => $c->description,
                    'created_at' => $c->created_at->format('M d, Y H:i'),
                    'updated_at' => $c->updated_at->format('M d, Y H:i'),
                ];
            });
            return response()->json(['complaints' => $data]);
        } catch (\Exception $e) {
            \Log::error('Complaint liveData error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get notification data for logged-in users (Manager, VM, NFO)
     * Returns unassigned and assign_to_me complaints
     */
    public function notificationData()
    {
        try {

            if (!auth()->check()) {
                return response()->json([
                    'unassigned' => 0,
                    'assign_to_me' => 0,
                    'complaints' => [],
                    'authenticated' => false
                ], 401);
            }

            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'unassigned' => 0,
                    'assign_to_me' => 0,
                    'complaints' => []
                ]);
            }

            if (
                !$user->isManager() &&
                !$user->isVM() &&
                !$user->isNFO()
            ) {
                return response()->json([
                    'unassigned' => 0,
                    'assign_to_me' => 0,
                    'complaints' => []
                ]);
            }

            $statusIds = Status::whereIn('name', [
                'unassigned',
                'assigned',
                'pending_with_vendor',
                'pending_with_user',
                'assign_to_me',
                'completed',
                'closed'
            ])->pluck('id', 'name');
            $baseQuery = Complaint::query();

            if ($user->isVM()) {
                $verticalIds = $user->verticals->pluck('id');
                $baseQuery->whereHas('verticals', function ($q) use ($verticalIds) {
                    $q->whereIn('verticals.id', $verticalIds);
                });
            }

            elseif ($user->isNFO()) {

                $verticalIds = $user->verticals->pluck('id');

                $baseQuery->whereHas('verticals', function ($q) use ($verticalIds) {
                    $q->whereIn('verticals.id', $verticalIds);
                })
                ->where('assigned_to', $user->id);
            }

            if ($user->isNFO()) {
                $unassignedCount = 0;
            } else {
                $unassignedCount = (clone $baseQuery)
                    ->where('status_id', $statusIds->get('unassigned'))
                    ->whereDate('created_at', today())
                    ->count();
            }

            $assignToMeQuery = Complaint::query();

            if ($user->isVM() || $user->isNFO()) {
                $verticalIds = $user->verticals->pluck('id');
                $assignToMeQuery->whereHas('verticals', function ($q) use ($verticalIds) {
                    $q->whereIn('verticals.id', $verticalIds);
                });
            }

            $assignToMeCount = $assignToMeQuery
                ->where('assigned_to', $user->id)
                ->whereDate('created_at', today())
                ->whereNotIn('status_id', [
                    $statusIds->get('closed'),
                    $statusIds->get('completed')
                ])
                ->count();

            $recentComplaints = (clone $baseQuery)
                ->with([
                    'assignedTo',
                    'status',
                    'verticals'
                ])
                ->whereDate('created_at', today())
                ->whereNotIn('status_id', [
                    $statusIds->get('closed'),
                    $statusIds->get('completed')
                ])
                ->latest()
                ->limit(5)
                ->get();

            $complaints = $recentComplaints->map(function ($c) {

                return [
                    'id' => $c->id,
                    'reference_number' => $c->reference_number,
                    'user_name' => $c->user_name,
                    'status' => $c->status?->display_name ?? 'Unknown',
                    'priority' => ucfirst($c->priority),
                    'assigned_to_name' => $c->assignedTo?->full_name ?? 'Unassigned',
                    'description' => $c->description,
                    'verticals' => $c->verticals
                        ->pluck('name')
                        ->map(fn ($name) => ucfirst($name))
                        ->implode(', '),
                    'created_at' => $c->created_at->format('M d, Y H:i'),
                ];
            });

            return response()->json([
                'unassigned' => $unassignedCount,
                'assign_to_me' => $assignToMeCount,
                'complaints' => $complaints,
                'status_ids' => [
                    'unassigned' => $statusIds['unassigned'] ?? null,
                    'assigned' => $statusIds['assigned'] ?? null,
                    'pending_with_vendor' => $statusIds['pending_with_vendor'] ?? null,
                    'pending_with_user' => $statusIds['pending_with_user'] ?? null,
                    'assign_to_me' => $statusIds['assign_to_me'] ?? null,
                    'completed' => $statusIds['completed'] ?? null,
                    'closed' => $statusIds['closed'] ?? null,
                ],

                'user_id' => $user->id,
                'today_date' => today()->toDateString(),
            ]);

        } catch (\Exception $e) {

            \Log::error(
                'Complaint notificationData error: ' . $e->getMessage()
            );

            return response()->json([
                'error' => 'Something went wrong while fetching notification data.'
            ], 500);
        }
    }

    public function sendHODReport()
    {
        try {
            $today = now()->format('M d, Y');

            // Get complaint statistics
            $totalComplaints = Complaint::whereDate('created_at', today())->count();
            $unassigned = Complaint::whereDate('created_at', today())
                ->whereHas('status', function($q) {
                    $q->where('name', 'unassigned');
                })->count();
            $completed = Complaint::whereDate('created_at', today())
                ->whereHas('status', function($q) {
                    $q->where('name', 'completed');
                })->count();
            $actionPending = Complaint::whereDate('created_at', today())
                ->whereHas('status', function($q) {
                    $q->whereIn('name', ['assigned', 'in_progress', 'pending_with_vendor', 'pending_with_user']);
                })->count();

            // Get usage report data (VMs and NFOs)
            $users = User::whereHas('role', function($query) {
                $query->whereIn('slug', ['vm', 'nfo']);
            })->with(['role'])->get();

            $usageData = [];
            $completedStatusIds = Status::whereIn('name', ['completed', 'closed'])->pluck('id');
            $pendingStatusIds = Status::whereNotIn('name', ['completed', 'closed'])->pluck('id');

            foreach ($users as $user) {
                $query = $user->assignedComplaints()->whereDate('created_at', today());
                $assignedComplaints = $query->get();

                $userCompleted = $assignedComplaints->whereIn('status_id', $completedStatusIds)->count();
                $userPending = $assignedComplaints->whereIn('status_id', $pendingStatusIds)->count();
                $userTotal = $assignedComplaints->count();

                $usageData[] = [
                    'name' => $user->full_name ?: $user->username,
                    'pending' => $userPending,
                    'completed' => $userCompleted,
                    'total' => $userTotal,
                    'completion_rate' => $userTotal > 0 ? round(($userCompleted / $userTotal) * 100, 2) : 0,
                ];
            }

            $reportData = [
                'date' => $today,
                'total_complaints' => $totalComplaints,
                'unassigned' => $unassigned,
                'completed' => $completed,
                'action_pending' => $actionPending,
                'usage_data' => $usageData,
            ];

            Mail::to(env('HOD_EMAIL'))->send(new HODReportMail($reportData));

            return response()->json([
                'success' => true,
                'message' => 'HOD report sent successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Send HOD Report error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send HOD report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkImport()
    {
        try {
            return view('complaints.bulk-import');
        } catch (\Exception $e) {
            \Log::error('Bulk import view error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while loading bulk import page.');
        }
    }

    public function downloadFormat()
    {
        try {
            $sections = Section::all(['id', 'name']);
            $networkTypes = NetworkType::all(['id', 'name']);
            $users = User::whereHas('role')->get(['id', 'full_name']);
            $verticals = Vertical::all(['id', 'name', 'parent_id']);
            $requestTypes = RequestType::all(['id', 'name']);
            $parentIds = $verticals->pluck('parent_id')->filter()->unique()->toArray();
            
            $verticalPaths = [];
            foreach ($verticals as $vertical) {
                if (in_array($vertical->id, $parentIds)) {
                    continue;
                }
                
                $verticalPaths[] = $this->getVerticalFullPath($vertical, $verticals);
            }
            sort($verticalPaths);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $masterSheet = $spreadsheet->createSheet();
            $masterSheet->setTitle('MasterData');

            $row = 1;
            foreach ($sections as $section) {
                $masterSheet->setCellValue('A' . $row, $section->name);
                $row++;
            }
            $sectionRange = 'MasterData!$A$1:$A$' . max(1, $row - 1);

            $row = 1;
            foreach ($networkTypes as $networkType) {
                $masterSheet->setCellValue('B' . $row, $networkType->name);
                $row++;
            }
            $networkTypeRange = 'MasterData!$B$1:$B$' . max(1, $row - 1);

            $row = 1;
            foreach ($verticalPaths as $path) {
                $masterSheet->setCellValue('C' . $row, $path);
                $row++;
            }
            $verticalRange = 'MasterData!$C$1:$C$' . max(1, $row - 1);

            $row = 1;
            foreach ($users as $user) {
                $masterSheet->setCellValue('D' . $row, $user->full_name);
                $row++;
            }
            $userRange = 'MasterData!$D$1:$D$' . max(1, $row - 1);

            $row = 1;
            foreach ($requestTypes as $requestType) {
                $masterSheet->setCellValue('E' . $row, $requestType->name);
                $row++;
            }
            $requestTypeRange = 'MasterData!$E$1:$E$' . max(1, $row - 1);

            $masterSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Complaints');

            $headers = [
                'A1' => 'User Name*',
                'B1' => 'Intercom*',
                'C1' => 'Room Number*',
                'D1' => 'Section*',
                'E1' => 'Network Type*',
                'F1' => 'Request Type*',
                'G1' => 'Vertical*',
                'H1' => 'Description*',
                'I1' => 'Priority'
            ];

            if (\Auth::check()) {
                $headers['J1'] = 'Assigned To';
            }

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9E1F2');
            }

            $sheet->setCellValue('A2', 'Harsh Singh');
            $sheet->setCellValue('B2', '1234');
            $sheet->setCellValue('C2', '101');
            $sheet->setCellValue('D2', $sections->first()->name ?? '');
            $sheet->setCellValue('E2', $networkTypes->first()->name ?? '');
            $sheet->setCellValue('F2', $requestTypes->first()->name ?? '');
            $sheet->setCellValue('G2', $verticalPaths[0] ?? '');
            $sheet->setCellValue('H2', 'Sample complaint description');
            $sheet->setCellValue('I2', 'medium');
            
            if (\Auth::check()) {
                $sheet->setCellValue('J2', ''); 
            }

            // Reusable Validation Functionality
            $listValidation = function($range) {
                $v = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
                $v->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $v->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                $v->setShowInputMessage(true);
                $v->setShowErrorMessage(true);
                $v->setShowDropDown(true);
                $v->setFormula1($range);
                return $v;
            };

            // Apply Validations
            $sheet->setDataValidation('D2:D100', $listValidation($sectionRange));
            $sheet->setDataValidation('E2:E100', $listValidation($networkTypeRange));
            $sheet->setDataValidation('F2:F100', $listValidation($requestTypeRange));
            $sheet->setDataValidation('G2:G100', $listValidation($verticalRange));

            // Priority Validation
            $priorityValidation = $listValidation('"medium,high"');
            $priorityValidation->setAllowBlank(true);
            $sheet->setDataValidation('I2:I100', $priorityValidation);

            if (\Auth::check()) {
                $assignedToValidation = $listValidation($userRange);
                $assignedToValidation->setAllowBlank(true);
                $sheet->setDataValidation('J2:J100', $assignedToValidation);
            }

            // Auto-size columns
            $highestColLetter = $sheet->getHighestColumn();
            foreach (range('A', $highestColLetter) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $spreadsheet->setActiveSheetIndex(0);

            $filename = 'complaints_import_format_' . date('Y-m-d') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            \Log::error('Download format error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to download format file.');
        }
    }

    private function getVerticalFullPath($vertical, $allVerticals)
    {
        $path = $vertical->name;
        while ($vertical->parent_id !== null) {
            $vertical = $allVerticals->firstWhere('id', $vertical->parent_id);
            if (!$vertical) break;
            $path = $vertical->name . ' -> ' . $path;
        }
        return $path;
    }

    public function bulkImportStore(Request $request)
    {
        try {
            $request->validate([
                'excel_file' => 'required|file|max:10240'
            ]);

            $file = $request->file('excel_file');
            $extension = $file->getClientOriginalExtension();

            if (!in_array(strtolower($extension), ['xlsx', 'xls'])) {
                return redirect()->back()->with('error', 'Invalid file type. Please upload a XLSX or XLS file.');
            }

            $filePath = $file->getPathname();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $errors = [];
            $successCount = 0;

            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $allVerticals = Vertical::all(['id', 'name', 'parent_id', 'short_form']);

            for ($row = 2; $row <= $highestRow; $row++) {
                $data = [];
                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $cellValue = $sheet->getCell($col . $row)->getValue();
                    $headerValue = $sheet->getCell($col . 1)->getValue();
                    if ($headerValue) {
                        $data[trim($headerValue)] = $cellValue;
                    }
                }

                if (empty(array_filter($data))) {
                    continue;
                }

                $validator = Validator::make($data, [
                    'User Name*' => 'required|string|max:255',
                    'Intercom*' => 'required|string|max:255',
                    'Room Number*' => 'required|integer',
                    'Section*' => 'required|string',
                    'Network Type*' => 'required|string',
                    'Request Type*' => 'required|string',
                    'Vertical*' => 'required|string',
                    'Description*' => 'required|string',
                    'Priority' => 'nullable|in:high,medium',
                    'Assigned To' => 'nullable|string'
                ]);

                if ($validator->fails()) {
                    $errors[] = "Row {$row}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                $section = Section::where('name', trim($data['Section*']))->first();
                if (!$section) {
                    $errors[] = "Row {$row}: Section '{$data['Section*']}' not found";
                    continue;
                }

                $networkType = NetworkType::where('name', trim($data['Network Type*']))->first();
                if (!$networkType) {
                    $errors[] = "Row {$row}: Network Type '{$data['Network Type*']}' not found";
                    continue;
                }

                $requestType = RequestType::where('name', trim($data['Request Type*']))->first();
                if (!$requestType) {
                    $errors[] = "Row {$row}: Request Type '{$data['Request Type*']}' not found";
                    continue;
                }

                $verticalPathString = $data['Vertical*'];
                $pathNames = array_map('trim', explode('->', $verticalPathString));
                
                $verticalIds = [];
                $currentParentId = null;
                $lookupFailed = false;

                foreach ($pathNames as $vName) {
                    $vNode = $allVerticals->where('name', $vName)
                                        ->where('parent_id', $currentParentId)
                                        ->first();
                    if ($vNode) {
                        $verticalIds[] = $vNode->id;
                        $currentParentId = $vNode->id;
                    } else {
                        $lookupFailed = true;
                        break;
                    }
                }

                if ($lookupFailed || empty($verticalIds)) {
                    $errors[] = "Row {$row}: Vertical path '{$verticalPathString}' is invalid or broken";
                    continue;
                }

                $assignedToId = null;
                $assignedById = null;
                
                if (!empty($data['Assigned To'])) {
                    $user = User::where('full_name', trim($data['Assigned To']))->first();
                    if ($user) {
                        $assignedToId = $user->id;
                        $assignedById = Auth::id();
                    }
                }
                
                $priority = $data['Priority'] ?? 'medium';
                $unassignedStatus = Status::where('name', 'unassigned')->first();

                $date = Carbon::now()->format('Ymd');
                
                $verticalsChain = Vertical::whereIn('id', $verticalIds)
                    ->orderByRaw('FIELD(id, ' . implode(',', $verticalIds) . ')')
                    ->get();

                $prefixParts = [];
                foreach ($verticalsChain as $v) {
                    if ($v->short_form) {
                        $prefixParts[] = strtoupper($v->short_form);
                    }
                }
                $prefix = !empty($prefixParts) ? implode('-', $prefixParts) : 'CMP';

                $complaintsToday = Complaint::whereDate('created_at', Carbon::today())->count();
                $referenceNumber = $prefix . '-' . $date . str_pad($complaintsToday + 1, 3, '0', STR_PAD_LEFT);

                $assignedStatus = Status::where('name', 'assigned')->first();
                $statusId = !empty($assignedToId) ? $assignedStatus->id : $unassignedStatus->id;

                $complaint = Complaint::create([
                    'reference_number' => $referenceNumber,
                    'client_id' => Auth::user()->id ?? 0,
                    'description' => $data['Description*'],
                    'priority' => $priority,
                    'status_id' => $statusId,
                    'network_type_id' => $networkType->id,
                    'request_type_id' => $requestType->id,
                    'section_id' => $section->id,
                    'user_name' => $data['User Name*'],
                    'room_number' => $data['Room Number*'],
                    'intercom' => (string)$data['Intercom*'],
                    'assigned_to' => $assignedToId,
                    'assigned_by' => $assignedById,
                    'created_at' => Carbon::now()->setTimezone(config('app.timezone')),
                    'updated_at' => Carbon::now()->setTimezone(config('app.timezone')),
                ]);

                $complaint->verticals()->sync($verticalIds);
                $complaint->load('verticals');

                $actionChanges = [
                    'verticals' => $complaint->verticals->pluck('name')->toArray()
                ];

                if (!empty($assignedToId)) {
                    $actionChanges['assigned_to'] = trim($data['Assigned To']);
                    $actionChanges['assigned_by'] = Auth::user()->full_name ?? 'System';
                }

                ComplaintAction::create([
                    'complaint_id' => $complaint->id,
                    'user_id' => Auth::user()->id ?? 0,
                    'status_id' => $statusId, 
                    'assigned_to' => $complaint->assigned_to ?: null,
                    'description' => 'Complaint created via bulk import',
                    'changes' => json_encode($actionChanges) 
                ]);

                try {
                    $notificationService = new ComplaintNotificationService();
                    $notificationService->sendNewComplaintNotifications($complaint);
                } catch (\Exception $e) {
                    \Log::error('Email notification failed in bulk import: ' . $e->getMessage());
                }

                $successCount++;
            }

            if (!empty($errors)) {
                return redirect()->back()->with('error', "Import completed with errors. Success: {$successCount}, Errors: " . count($errors) . '. ' . implode('; ', array_slice($errors, 0, 5)));
            }

            return redirect()->route('complaints.bulk-import')->with('success', "Successfully imported {$successCount} complaints.");

        } catch (\Exception $e) {
            \Log::error('Bulk import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while importing complaints: ' . $e->getMessage());
        }
    }

}
