@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Dashboard</h4>
                    <!-- <div>
                        @if(auth()->user()->isManager() || auth()->user()->isVM())
                        <a href="{{ route('complaints.index') }}" class="btn btn-primary">View All Complaints</a>
                        @endif
                    </div> -->
                </div>

                <div class="card-body">
                    <!-- Welcome Message -->
                    <div class="alert alert-info">
                        Welcome back, {{ auth()->user()->name }}!
                        @if(auth()->user()->isManager())
                        As a Manager, you can view and assign all complaints.
                        @elseif(auth()->user()->isVM())
                        As a Vendor Manager, you can self-assign complaints and assign them to NFOs.
                        @elseif(auth()->user()->isNFO())
                        As a Network Field Officer, you can resolve complaints and reassign them.
                        @endif
                    </div>

                    <!-- Statistics (from controller variables) -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-12 mb-4">
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <a href="{{ route('complaints.index') }}" class="card-link-stretched text-decoration-none">
                                        <div class="card shadow-lg border-0 rounded-4 bg-primary text-white h-100 clickable-card">
                                            <div class="card-body text-center py-4">
                                                <h5 class="card-title mb-2">Total Tickets</h5>
                                                <h2 class="fw-bold mb-0 display-5">{{ $totalComplaints }}</h2>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @if(!auth()->user()->isNFO())
                                <div class="col-md-3">
                                    <a href="{{ route('complaints.index', ['status' => $unassignedStatusId ?? '']) }}" class="card-link-stretched text-decoration-none">
                                        <div class="card shadow-lg border-0 rounded-4 bg-warning text-dark h-100 clickable-card">
                                            <div class="card-body text-center py-4">
                                                <h5 class="card-title mb-2">Unassigned</h5>
                                                <h2 class="fw-bold mb-0 display-5">{{ $unassignedComplaints }}</h2>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                <div class="col-md-3">
                                    <a href="{{ route('complaints.index', ['status' => $completedStatusId ?? '']) }}" class="card-link-stretched text-decoration-none">
                                        <div class="card shadow-lg border-0 rounded-4 bg-success text-white h-100 clickable-card">
                                            <div class="card-body text-center py-4">
                                                <h5 class="card-title mb-2">Completed</h5>
                                                <h2 class="fw-bold mb-0 display-5">{{ $completedComplaints }}</h2>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('complaints.index', ['assigned_to_me' => 1]) }}" class="card-link-stretched text-decoration-none">
                                        <div class="card shadow-lg border-0 rounded-4 bg-info text-white h-100 clickable-card">
                                            <div class="card-body text-center py-4">
                                                <h5 class="card-title mb-2">Assign to Me</h5>
                                                <h2 class="fw-bold mb-0 display-5">{{ $assignToMeComplaints }}</h2>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Complaints -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-lg border-0 rounded-4 mt-2">
                                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);">
                                    <h4 class="mb-0">Today's Tickets</h4>
                                    <span class="badge bg-light text-primary fs-6">{{ $todayComplaints->count() }} Today</span>
                                </div>
                                <div class="card-body">
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
                                                @forelse($todayComplaints as $complaint)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $complaint->reference_number }}</td>
                                                    <td>{{ $complaint->user_name  ?? '-'}}</td>
                                                    <td>{{ $complaint->section  ? $complaint->section->name : ''}}</td>
                                                    <td>{{ $complaint->networkType?->name ?? 'N/A' }}</td>
                                                    <td>{{ $complaint->vertical?->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $complaint->status_color }}">
                                                            {{ $complaint->status?->display_name ?? 'Unknown' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $complaint->priority_color ?? 'secondary' }}">
                                                            {{ ucfirst($complaint->priority) ?? 'Unknown' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $complaint->assignedTo?->full_name ?? 'Not Assigned' }}</td>
                                                    <td>
                                                        @php $assignedBy = $complaint->assigned_by ? \App\Models\User::find($complaint->assigned_by) : null; @endphp
                                                        {{ $assignedBy?->full_name ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-primary">View</a>
                                                        @auth
                                                        @if((auth()->user()->isManager() || auth()->user()->isVM()) && (!$complaint->assigned_to || $complaint->assigned_to == 0) && $complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                                                            Assign
                                                        </button>
                                                        @endif
                                                        @endauth
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No complaints today.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <!-- Render all assign modals after the table for DataTables compatibility -->
                                        @foreach($todayComplaints as $complaint)
                                        @if((auth()->user()->isManager() || auth()->user()->isVM()) && (!$complaint->assigned_to || $complaint->assigned_to == 0) && $complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                                        <div class="modal fade" id="assignModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $complaint->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('complaints.assign', $complaint) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="assignModalLabel{{ $complaint->id }}">Assign Ticket {{ $complaint->reference_number }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="assigned_to{{ $complaint->id }}" class="form-label">Assign To</label>
                                                                <select class="form-select" name="assigned_to" id="assigned_to{{ $complaint->id }}" required>
                                                                    <option value="">Select User</option>
                                                                    @foreach($complaint->assignableUsers as $user)
                                                                    <option value="{{ $user->id }}">{{ $user->full_name }} ({{ strtoupper($user->role->name) }})</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="description{{ $complaint->id }}" class="form-label">Remarks</label>
                                                                <textarea class="form-control" name="description" id="description{{ $complaint->id }}" rows="3" required></textarea>
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
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.bootstrap5.min.css') }}">
@endsection

@section('scripts')
{{-- <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script> --}} <!-- Removed duplicate jQuery -->
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.print.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#complaintsTable').DataTable({
            responsive: false, // Disable responsive extension
            scrollX: true,     // Enable horizontal scrolling
            order: [
                [1, 'desc']
            ], // Reference column descending
            pageLength: 10,
            lengthMenu: [
                [10, 15, 20, 50, 100, -1],
                [10, 15, 20, 50, 100, 'All']
            ],
            language: {
                search: "",
                searchPlaceholder: "Search complaints..."
            },
            dom: 'lfrtip',
            columnDefs: [{
                    orderable: false,
                    targets: 0
                } // Disable sorting on S.No.
            ]
        });
    });
</script>
@endsection

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

    .card-link-stretched {
        text-decoration: none;
        display: block;
    }

    .clickable-card {
        cursor: pointer;
        transition: transform 0.12s, box-shadow 0.12s;
    }

    .clickable-card:hover,
    .clickable-card:focus {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 12px 36px 0 rgba(31, 38, 135, 0.18);
        z-index: 2;
        text-decoration: none;
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

@stack('scripts')