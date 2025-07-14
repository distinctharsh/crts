@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' => route('dashboard')],
['label' => 'Tickets', 'url' => route('complaints.index')],
];
@endphp

<style>
    div.dataTables_wrapper div.dataTables_filter input {
        width: 400px;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 mt-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);">
                    <h4 class="mb-0">All Tickets</h4>
                    <div class="">

                        @include('layouts.partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="mb-4">
                        <div class="card filter-card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-gradient-primary text-white py-2 rounded-top-3"
                                style="cursor:pointer;"
                                data-bs-toggle="collapse"
                                data-bs-target="#filterCollapse"
                                aria-expanded="{{ (request('status') || request('by') || request('vertical') || request('networktype') || request('section') || request('date_from') || request('date_to')) ? 'true' : 'false' }}"
                                aria-controls="filterCollapse">
                                <strong class="text-dark">Filter Tickets</strong>
                                <span class="float-end bg-secondary" style="padding: 5px 10px; border-radius: 8px;">
                                    <i class="bi {{ (request('status') || request('by') || request('vertical') || request('networktype') || request('section') || request('date_from') || request('date_to')) ? 'bi-chevron-up' : 'bi-chevron-down' }}" id="filterChevron"></i>
                                </span>
                            </div>
                            <div class="collapse{{ (request('status') || request('by') || request('vertical') || request('networktype') || request('section') || request('date_from') || request('date_to')) ? ' show' : '' }}" id="filterCollapse">
                                <div class="card-body py-3">
                                    <form method="GET" action="{{ route('complaints.index') }}">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Status</label>
                                                <select name="status" class="form-select tom-select">
                                                    <option value="">All Status</option>
                                                    @foreach($statuses as $status)
                                                    <option value="{{ $status->id }}" {{ collect(request('status'))->contains($status->id) ? 'selected' : '' }}>
                                                        {{ $status->display_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Assigned To</label>
                                                <select name="by" class="form-select tom-select">
                                                    <option value="">Assigned To</option>
                                                    @foreach($usersList as $user)
                                                    <option value="{{ $user->id }}" {{ request('by') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->full_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Vertical</label>
                                                <select name="vertical" class="form-select tom-select">
                                                    <option value="">All Vertical</option>
                                                    @foreach($verticals as $vertical)
                                                    <option value="{{ $vertical->id }}" {{ request('vertical') == $vertical->id ? 'selected' : '' }}>
                                                        {{ $vertical->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Network Type</label>
                                                <select name="networktype" class="form-select tom-select">
                                                    <option value="">All Issue Type</option>
                                                    @foreach($networkTypes as $networktype)
                                                    <option value="{{ $networktype->id }}" {{ request('networktype') == $networktype->id ? 'selected' : '' }}>
                                                        {{ $networktype->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Section</label>
                                                <select name="section" class="form-select tom-select">
                                                    <option value="">All Section</option>
                                                    @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>
                                                        {{ $section->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">From</label>
                                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">To</label>
                                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                            </div>
                                            <div class="col-md-1 d-grid ms-auto">
                                                <button class="btn btn-secondary rounded-pill px-3" type="submit">
                                                    Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter Form -->
                    <div class="table-responsive">
                        <table id="complaintsTable" class="table table-hover table-bordered table-striped align-middle w-100">
                            <thead class="table-primary">
                                <tr>
                                    <th class="no-sort">S.No.</th>
                                    <th>Reference</th>
                                    <th>User</th>
                                    <th>Section</th>
                                    <th>Network</th>
                                    <th>Vertical</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Assigned To</th>
                                    <th>Assigned By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $complaint)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $complaint->reference_number }}</td>
                                    <td>{{ $complaint->user_name }}</td>
                                    <td>{{ $complaint->section->name }}</td>
                                    <td>{{ $complaint->networkType->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->vertical->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $complaint->status_color }}">
                                            {{ $complaint->status->display_name ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $complaint->priority_color }}">
                                            {{ ucfirst($complaint->priority) }}
                                        </span>
                                    </td>
                                    <td>{{ $complaint->assignedTo?->full_name ?? 'Not Assigned' }}</td>
                                    <td>
                                        @php $assignedBy = $complaint->assigned_by ? \App\Models\User::find($complaint->assigned_by) : null; @endphp
                                        {{ $assignedBy?->full_name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-info me-1">View</a>
                                            @auth
                                            @if(auth()->user()->isManager())
                                            @if($complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                                                @if($complaint->assigned_to)
                                                Reassign
                                                @else
                                                Assign
                                                @endif
                                            </button>
                                            @endif
                                            @elseif(auth()->user()->isVM())
                                            @if(($complaint->isUnassigned() || $complaint->assigned_to === auth()->user()->id) && $complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                                                Assign
                                            </button>
                                            @if($complaint->assigned_to === auth()->user()->id && $complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#revertModal{{ $complaint->id }}">
                                                Revert
                                            </button>
                                            @endif
                                            @endif
                                            @elseif(auth()->user()->isNFO())
                                          
                                            @if($complaint->assigned_to === auth()->user()->id && !$complaint->isCompleted() && !$complaint->isClosed())
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                                                Reassign
                                            </button>
                                            @endif
                                            @endif
                                            @endauth
                                        </div>
                                        <!-- Assign Modal -->
                                        <div class="modal fade" id="assignModal{{ $complaint->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('complaints.assign', $complaint) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Assign Complaint</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="assigned_to" class="form-label">Assign To</label>
                                                                <select class="form-select tom-select" name="assigned_to" required>
                                                                    <option value="">Select User</option>
                                                                    @foreach($complaint->assignableUsers as $user)
                                                                    <option value="{{ $user->id }}">{{ $user->full_name }} ({{ strtoupper($user->role->name) }})</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="description" class="form-label">Remarks</label>
                                                                <textarea class="form-control" name="description" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Assign</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Resolve Modal -->
                                        <div class="modal fade" id="resolveModal{{ $complaint->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('complaints.resolve', $complaint) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Resolve Ticket</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-check mb-3">
                                                                <input class="form-check-input" type="checkbox" value="1" id="markClosed{{ $complaint->id }}" name="mark_closed">
                                                                <label class="form-check-label" for="markClosed{{ $complaint->id }}">
                                                                    Mark as Closed
                                                                </label>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="status_id" class="form-label">Status *</label>
                                                                <select class="form-select tom-select" id="statusSelect{{ $complaint->id }}" name="status_id" required>
                                                                    @foreach($statuses as $status)
                                                                    <option value="{{ $status->id }}" {{ old('status_id', $complaint->status_id) == $status->id ? 'selected' : '' }}>
                                                                        {{ $status->display_name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="description" class="form-label">Remarks / Solution *</label>
                                                                <textarea class="form-control" name="description" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">Resolve</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Revert Modal -->
                                        <div class="modal fade" id="revertModal{{ $complaint->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('complaints.revert', $complaint) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Revert to Manager</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="assigned_to" class="form-label">Revert to Manager</label>
                                                                <select class="form-select tom-select" name="assigned_to" required>
                                                                    <option value="">Select Manager</option>
                                                                    @foreach($managers as $manager)
                                                                    <option value="{{ $manager->id }}" @if($manager->id == $complaint->assigned_by) selected @endif>
                                                                        {{ $manager->full_name }}@if($manager->id == $complaint->assigned_by) (Original Assigner)@endif
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="description" class="form-label">Reason for Reverting</label>
                                                                <textarea class="form-control" name="description" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-warning">Revert</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var dt = $('#complaintsTable').DataTable({
            responsive: false, // Disable responsive extension
            scrollX: true,     // Enable horizontal scrolling
            order: [[1, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 15, 20, 50, 100, -1], [10, 15, 20, 50, 100, 'All']],
            language: {
                search: "",
                searchPlaceholder: "Search complaints..."
            },
            dom: '<"d-flex justify-content-between align-items-center mb-2"Bfl>rtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="bi bi-clipboard"></i>',
                    className: 'btn btn-light btn-sm me-1',
                    titleAttr: 'Copy'
                },
                {
                    extend: 'csv',
                    text: '<i class="bi bi-filetype-csv"></i>',
                    className: 'btn btn-light btn-sm me-1',
                    titleAttr: 'Export as CSV'
                },
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i>',
                    className: 'btn btn-light btn-sm me-1',
                    titleAttr: 'Export as Excel'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf"></i>',
                    className: 'btn btn-light btn-sm me-1',
                    titleAttr: 'Export as PDF'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i>',
                    className: 'btn btn-light btn-sm',
                    titleAttr: 'Print'
                }
            ],
            columnDefs: [
                { orderable: false, targets: 0 }
            ],
            drawCallback: function() {
                // Re-initialize modals or tooltips if needed
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var filterCollapse = document.getElementById('filterCollapse');
        var chevron = document.getElementById('filterChevron');
        var collapseInstance = bootstrap.Collapse.getOrCreateInstance(filterCollapse, {
            toggle: false
        });

        // Helper: check if any filter is set
        var isAnyFilterSet = {{ (request('status') || request('by') || request('vertical') || request('networktype') || request('section') || request('date_from') || request('date_to')) ? 'true' : 'false' }};

        // On load: decide open/close
        var filterState = localStorage.getItem('complaintsFilterOpen');
        if (filterState === 'open') {
            collapseInstance.show();
            chevron.classList.remove('bi-chevron-down');
            chevron.classList.add('bi-chevron-up');
        } else if (filterState === 'closed') {
            collapseInstance.hide();
            chevron.classList.remove('bi-chevron-up');
            chevron.classList.add('bi-chevron-down');
        } else {
            // Default: open if any filter set, else closed
            if (isAnyFilterSet) {
                collapseInstance.show();
                chevron.classList.remove('bi-chevron-down');
                chevron.classList.add('bi-chevron-up');
            } else {
                collapseInstance.hide();
                chevron.classList.remove('bi-chevron-up');
                chevron.classList.add('bi-chevron-down');
            }
        }

        // On collapse/expand, update localStorage and chevron
        filterCollapse.addEventListener('show.bs.collapse', function() {
            localStorage.setItem('complaintsFilterOpen', 'open');
            chevron.classList.remove('bi-chevron-down');
            chevron.classList.add('bi-chevron-up');
        });
        filterCollapse.addEventListener('hide.bs.collapse', function() {
            localStorage.setItem('complaintsFilterOpen', 'closed');
            chevron.classList.remove('bi-chevron-up');
            chevron.classList.add('bi-chevron-down');
        });

        // Tom Select initialization for all .tom-select dropdowns
        document.querySelectorAll('select.tom-select').forEach(function(el) {
            new TomSelect(el, {
                create: false,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                }
            });
        });
    });
</script>
@endpush

@push('style')
<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%) !important;
        color: #fff !important;
    }

    .card {
        border-radius: 22px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.13);
        border: none;
        margin-bottom: 2rem;
        transition: box-shadow 0.2s;
    }

    .card-header {
        border-radius: 22px 22px 0 0;
        font-weight: 700;
        font-size: 1.18rem;
        letter-spacing: 0.7px;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.07);
    }

    .table-primary {
        background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%) !important;
        color: #fff !important;
        font-size: 1.08rem;
        letter-spacing: 0.5px;
    }

    .table-bordered {
        border-radius: 14px;
        overflow: hidden;
    }

    .table-hover tbody tr:hover {
        background: #f0f6ff !important;
        transition: background 0.2s;
    }

    .dataTables_filter input[type="search"] {
        width: 350px !important;
        font-size: 1.1rem !important;
        padding: 0.5rem 1rem !important;
    }

    /* Modern Table Enhancements */
    .table-responsive {
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        overflow-x: auto;
        background: #fff;
        margin-bottom: 1.5rem;
    }

    #complaintsTable {
        min-width: 1200px;
        border-radius: 12px;
        overflow: hidden;
    }

    #complaintsTable thead th {
        position: sticky;
        top: 0;
        background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%) !important;
        color: #fff !important;
        z-index: 2;
    }

    #complaintsTable tbody tr:hover {
        background: #eaf1fb !important;
        transition: background 0.2s;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
</style>
@endpush