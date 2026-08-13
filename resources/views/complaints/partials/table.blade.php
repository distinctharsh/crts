@php
    $showPerPage = $showPerPage ?? true;
@endphp

<style>
    .table-responsive {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        padding: 4px;
        border: 1px solid #e2e8f0;
    }
    
    .crt-modern-table {
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }
    
    .crt-modern-table thead {
        background: linear-gradient(135deg, #1e40af 0%, #0284c7 100%);
    }
    
    .crt-modern-table thead th {
        color: #ffffff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        padding: 16px 14px;
        border: none;
    }
    
    .crt-modern-table thead th:first-child { border-radius: 8px 0 0 0; }
    .crt-modern-table thead th:last-child { border-radius: 0 8px 0 0; }
    
    .crt-modern-table tbody tr {
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    
    .crt-modern-table tbody tr:hover {
        background-color: #f8fafc !important;
        box-shadow: inset 4px 0 0 0 #3b82f6;
    }
    
    .crt-modern-table tbody td {
        padding: 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.85rem;
    }
    
    .crt-ref-id {
        font-family: 'SF Mono', 'JetBrains Mono', monospace;
        font-weight: 700;
        color: #0f172a;
        font-size: 0.88rem;
    }
</style>

<!-- Top Right Controls (Conditionally Hidden if showPerPage is false) -->
@if($showPerPage)
<div class="d-flex justify-content-between align-items-center mb-2 px-1">
    <div></div>
    <div class="d-flex align-items-center gap-2">
        <label for="perPageSelect" class="form-label mb-0 text-muted small fw-bold">Show</label>
        <select id="perPageSelect" class="form-select form-select-sm" style="width: auto;">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 entries</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 entries</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 entries</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 entries</option>
            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
        </select>
    </div>
</div>
@endif

<div class="table-responsive">
<table id="{{ $tableId ?? 'complaintsTable' }}" class="table table-hover table-bordered table-striped align-middle w-100">
    <thead class="table-primary">
        <tr>
            <th class="no-sort">S.No.</th>
            <th>Reference</th>
            <th>User</th>
            <th>Section</th>
            <th>Network</th>
            <th>Request Type</th>
            <th>Category</th>
            <th>Assigned To</th>
            <th>Description</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($complaints as $complaint)
        <tr data-complaint-id="{{ $complaint->id }}" data-status="{{ $complaint->status->name }}" data-assigned-to="{{ $complaint->assigned_to }}" data-user-role="{{ auth()->check() ? auth()->user()->role->name : 'guest' }}" data-is-unassigned="{{ $complaint->isUnassigned() ? 'true' : 'false' }}" data-is-completed="{{ $complaint->isCompleted() ? 'true' : 'false' }}" data-is-closed="{{ $complaint->isClosed() ? 'true' : 'false' }}">
            <td class="text-center">{{ $loop->iteration }}</td>
            <td style="word-break: break-all;">
                <div class="crt-ref-id">{{ $complaint->reference_number }}</div>
                <div class="mt-1">
                    <span class="badge bg-{{ $complaint->status_color }} status-badge" style="font-size: 0.75rem;">
                        {{ $complaint->status->display_name ?? 'Unknown' }}
                    </span>
                    @if($complaint->priority === 'high')
                    <span class="badge bg-danger" style="font-size: 0.75rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i> High
                    </span>
                    @endif
                </div>
                <div class="mt-1 small text-muted" style="font-size: 0.72rem;">
                    <i class="bi bi-clock"></i> {{ $complaint->created_at->format('d M Y, H:i') }}
                </div>
            </td>
            <td>{{ $complaint->user_name }}</td>
            <td>{{ $complaint->section->name }}</td>
            <td>{{ $complaint->networkType->name ?? 'N/A' }}</td>
            <td>{{ $complaint->requestType->name ?? 'N/A' }}</td>
            <td>{{ $complaint->vertical ? $complaint->vertical->full_path : 'N/A' }}</td>
            <td class="assigned-to-cell">{{ $complaint->assignedTo?->full_name ?? 'Not Assigned' }}</td>
            <td style="word-break: break-word; min-width: 150px; max-width: 250px;" title="{{ $complaint->description }}">
                {{ \Illuminate\Support\Str::limit($complaint->description, 50, '...') }}
            </td>
            <td>
                <div class="btn-group action-buttons">
                    <!-- View -->
                    <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-info text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="View Ticket"><i class="bi bi-eye"></i></a>
                    @auth
                    {{-- Status Update Comment Modal Button --}}
                    @if($complaint->assigned_to == auth()->user()->id && !$complaint->isCompleted() && !$complaint->isClosed())
                        <button type="button" class="btn btn-sm btn-warning text-dark ms-1 btn-open-status-modal" 
                                data-id="{{ $complaint->id }}" 
                                data-ref="{{ $complaint->reference_number }}"
                                data-status-id="{{ $complaint->status_id }}"
                                title="Update Status">
                            <i class="bi bi-chat-left-dots"></i>
                        </button>
                    @endif

                    {{-- Edit Ticket --}}
                    @if(
                        (auth()->user()->isManager() && $complaint->status->name != 'closed') || 
                        ((auth()->user()->isVM() || auth()->user()->isNFO()) && $complaint->status->name != 'closed' && $complaint->status->name != 'completed')
                    )
                        <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-sm btn-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Ticket"><i class="bi bi-pencil-square"></i></a>
                    @endif
                    
                    {{-- Role Based Action Buttons --}}
                    @if(auth()->user()->isManager())
                        @if($complaint->status->name == 'completed')
                            <button type="button" class="btn btn-sm btn-success ms-1 btn-open-close-modal" 
                                    data-id="{{ $complaint->id }}" 
                                    data-ref="{{ $complaint->reference_number }}" 
                                    title="Close Ticket">
                                <i class="bi bi-check-circle"></i>
                            </button>
                        @endif
                        
                        @if($complaint->status->name != 'closed')
                            <button type="button" class="btn btn-sm btn-primary ms-1 btn-open-assign-modal" 
                                    data-id="{{ $complaint->id }}" 
                                    data-ref="{{ $complaint->reference_number }}"
                                    data-assigned-to="{{ $complaint->assigned_to }}"
                                    title="{{ $complaint->assigned_to ? 'Reassign Ticket' : 'Assign Ticket' }}">
                                @if($complaint->assigned_to)
                                    <i class="bi bi-arrow-repeat"></i>
                                @else
                                    <i class="bi bi-person-check"></i>
                                @endif
                            </button>
                        @endif
                    @elseif(auth()->user()->isVM())
                        @if($complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                            <button type="button" class="btn btn-sm btn-primary ms-1 btn-open-assign-modal" 
                                    data-id="{{ $complaint->id }}" 
                                    data-ref="{{ $complaint->reference_number }}"
                                    data-assigned-to="{{ $complaint->assigned_to }}"
                                    title="{{ $complaint->assigned_to ? 'Reassign Ticket' : 'Assign Ticket' }}">
                                @if($complaint->assigned_to)
                                    <i class="bi bi-arrow-repeat"></i>
                                @else
                                    <i class="bi bi-person-check"></i>
                                @endif
                            </button>
                        @endif
                    @elseif(auth()->user()->isNFO())
                        @if($complaint->assigned_to === auth()->user()->id && !$complaint->isCompleted() && !$complaint->isClosed())
                            <button type="button" class="btn btn-sm btn-primary ms-1 btn-open-assign-modal" 
                                    data-id="{{ $complaint->id }}" 
                                    data-ref="{{ $complaint->reference_number }}"
                                    data-assigned-to="{{ $complaint->assigned_to }}"
                                    title="Reassign Ticket">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        @endif
                    @endif
                    @endauth
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table> 

<!-- Table Footer / Single Clean Pagination (Conditionally Hidden if showPerPage is false) -->
@if($showPerPage)
<div class="d-flex justify-content-between align-items-center mt-3 px-2 flex-wrap gap-2">
    <div class="text-muted small">
        @if(method_exists($complaints, 'firstItem'))
            Showing {{ $complaints->firstItem() ?? 0 }} to {{ $complaints->lastItem() ?? 0 }} of {{ $complaints->total() }} entries
        @else
            Showing {{ $complaints->count() }} entries
        @endif
    </div>
    <div>
        @if(method_exists($complaints, 'links'))
            {{ $complaints->links('pagination::bootstrap-5') }}
        @endif
    </div>
</div>
@endif
</div>