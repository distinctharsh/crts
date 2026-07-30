@php
// $complaints: the list of complaints to show
// $tableId: optional, for DataTable initialization
@endphp

<style>
    /* Premium Dashboard Wrapper Container */
    .table-responsive {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        padding: 4px;
        border: 1px solid #e2e8f0;
    }
    
    /* Clean Minimal Table Setup */
    .crt-modern-table {
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }
    
    /* Elegant Government Tech Header Lineage */
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
    
    /* Table Header Borders Smooth Curve */
    .crt-modern-table thead th:first-child { border-radius: 8px 0 0 0; }
    .crt-modern-table thead th:last-child { border-radius: 0 8px 0 0; }
    
    /* Row Transitions & Bordering */
    .crt-modern-table tbody tr {
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    
    .crt-modern-table tbody tr:hover {
        background-color: #f8fafc !important;
        box-shadow: inset 4px 0 0 0 #3b82f6; /* Left side indicators on hover */
    }
    
    .crt-modern-table tbody td {
        padding: 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.85rem;
    }
    
    /* Subtle Serial Number Styling */
    .crt-sno {
        font-weight: 700;
        color: #94a3b8;
    }
    
    /* Ticket ID / Reference Branding */
    .crt-ref-id {
        font-family: 'SF Mono', 'JetBrains Mono', monospace;
        font-weight: 700;
        color: #0f172a;
        font-size: 0.88rem;
    }
    
    /* Compact High-End Status Pill System */
    .crt-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    /* Priority Pill */
    .crt-badge-priority {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 600;
        background-color: #fef2f2;
        color: #ef4444;
        border: 1px solid #fee2e2;
    }
    
    /* User & Section Text Layout */
    .crt-text-main {
        font-weight: 600;
        color: #1e293b;
    }
    .crt-text-sub {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 2px;
    }
    
    /* Dynamic pill styles for Categories & Networks */
    .crt-pill-gray {
        background-color: #f1f5f9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.78rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .crt-assigned-user {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
        color: #0f172a;
    }
    
    /* Action Buttons Design Architecture */
    .crt-btn-action {
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 6px 12px;
        transition: all 0.15s ease;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .crt-btn-view { background-color: #f8fafc; color: #334155; border-color: #cbd5e1; }
    .crt-btn-view:hover { background-color: #e2e8f0; color: #0f172a; }
    
    .crt-btn-edit { background-color: #edf2f7; color: #2b6cb0; border-color: #bee3f8; }
    .crt-btn-edit:hover { background-color: #3182ce; color: #ffffff; }
    
    .crt-btn-assign { background-color: #ebf8ff; color: #2b6cb0; border-color: #bee3f8; }
    .crt-btn-assign:hover { background-color: #2b6cb0; color: #ffffff; }
    
    /* Limited multi-line description wrap safely */
    .crt-desc-box {
        color: #475569;
        font-size: 0.82rem;
        line-height: 1.4;
        max-width: 200px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>


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
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($complaints as $complaint)
        <tr data-complaint-id="{{ $complaint->id }}" data-status="{{ $complaint->status->name }}" data-assigned-to="{{ $complaint->assigned_to }}" data-user-role="{{ auth()->check() ? auth()->user()->role->name : 'guest' }}" data-is-unassigned="{{ $complaint->isUnassigned() ? 'true' : 'false' }}" data-is-completed="{{ $complaint->isCompleted() ? 'true' : 'false' }}" data-is-closed="{{ $complaint->isClosed() ? 'true' : 'false' }}">
            <td>{{ $loop->iteration }}</td>
            <td style="word-break: break-all;">
                {{ $complaint->reference_number }}
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
                    <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-info me-1">View</a>
                    @auth
                    @if(
                        (auth()->user()->isManager() && $complaint->status->name != 'closed') || 
                        (auth()->user()->isVM() && $complaint->status->name != 'closed' && $complaint->status->name != 'completed')
                    )
                        <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                    @endif
                    @endauth
                    
                    @auth
                    @if(auth()->user()->isManager())
                        @if($complaint->status->name == 'completed')
                            <button type="button" class="btn btn-sm btn-success ms-1 btn-close-ticket" data-bs-toggle="modal" data-bs-target="#closeModal{{ $complaint->id }}">
                                Close
                            </button>
                        @endif
                    @if($complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                    <button type="button" class="btn btn-sm btn-primary btn-assign-reassign" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                        @if($complaint->assigned_to)
                        Reassign
                        @else
                        Assign
                        @endif
                    </button>
                    @endif
                    @elseif(auth()->user()->isVM())
                    @if($complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                    <button type="button" class="btn btn-sm btn-primary btn-assign-reassign" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                        @if($complaint->assigned_to)
                        Reassign
                        @else
                        Assign
                        @endif
                    </button>
                    @endif
                    @elseif(auth()->user()->isNFO())
                    @if($complaint->assigned_to === auth()->user()->id && !$complaint->isCompleted() && !$complaint->isClosed())
                    <button type="button" class="btn btn-sm btn-primary btn-assign-reassign" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                        Reassign
                    </button>
                    @endif
                    @endif
                    @endauth
                </div>
                {{-- Modals for assign/resolve can be included here if needed --}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table> 
</div>