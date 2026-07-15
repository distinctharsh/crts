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
                        As a Team Lead, you can self-assign complaints and assign them to NFOs.
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
                                                <h5 class="card-title mb-2">Completed & Closed</h5>
                                                <h2 class="fw-bold mb-0 display-5">
                                                    {{ ($completedComplaints ?? 0) + ($closedComplaints ?? 0) }}
                                                </h2>
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

                    @php
                        $allComplaintsCollection = isset($complaints) ? $complaints : $todayComplaints;
                        $todayTickets = $allComplaintsCollection->filter(function($complaint) {
                            return \Carbon\Carbon::parse($complaint->created_at)->isToday();
                        });

                        $previousTickets = $allComplaintsCollection->filter(function($complaint) {
                            $isToday = \Carbon\Carbon::parse($complaint->created_at)->isToday();
                            $isDone = in_array(strtolower($complaint->status->name ?? ''), ['completed', 'closed']) 
                                    || ($complaint->status_id == ($completedStatusId ?? null));
                            return !$isToday && !$isDone;
                        });
                    @endphp

                    <!-- Today's Complaints -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card shadow-lg border-0 rounded-4">
                                <div class="card-header text-white rounded-top-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);">
                                    <h4 class="mb-0">Today's Tickets</h4>
                                    <span class="badge bg-light text-primary fs-6">{{ $todayTickets->count() }} Today</span>
                                </div>
                                <div class="card-body">
                                    @include('complaints.partials.table', ['complaints' => $todayTickets, 'tableId' => 'todayComplaintsTable'])
                                    @foreach($todayTickets as $complaint)
                                        @include('complaints.partials.assign-modal', ['complaint' => $complaint])
                                        @include('complaints.partials.revert-modal', ['complaint' => $complaint, 'managers' => $managers])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Previous Pending Tickets Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-lg border-0 rounded-4">
                                <div class="card-header text-white rounded-top-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #6c757d 0%, #495057 100%);">
                                    <h4 class="mb-0">Previous Pending Tickets</h4>
                                    <span class="badge bg-light text-secondary fs-6">{{ $previousTickets->count() }} Pending</span>
                                </div>
                                <div class="card-body">
                                    @include('complaints.partials.table', ['complaints' => $previousTickets, 'tableId' => 'previousComplaintsTable'])
                                    @foreach($previousTickets as $complaint)
                                        @include('complaints.partials.assign-modal', ['complaint' => $complaint])
                                        @include('complaints.partials.revert-modal', ['complaint' => $complaint, 'managers' => $managers])
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
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.bootstrap5.min.css') }}">
@endsection

@section('scripts')
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
        $('#todayComplaintsTable').DataTable({
            responsive: false,
            scrollX: true,
            order: [[1, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 15, 20, 50, 100, -1], [10, 15, 20, 50, 100, 'All']],
            language: { search: "", searchPlaceholder: "Search today's complaints..." },
            dom: 'lfrtip',
            columnDefs: [{ orderable: false, targets: 0 }]
        });

        // Previous Table
        $('#previousComplaintsTable').DataTable({
            responsive: false,
            scrollX: true,
            order: [[1, 'desc']],
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

@stack('scripts')