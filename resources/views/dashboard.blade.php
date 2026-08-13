@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daily Dashboard</h4>
                </div>

                <div class="card-body">
                    <!-- Welcome Message -->
                    <div class="alert alert-info">
                        👋 Welcome back, {{ auth()->user()->full_name }}! 
                        @if(auth()->user()->isManager())
                            Have a great day ahead! You're all set to manage and oversee the tickets smoothly.
                        @elseif(auth()->user()->isVM())
                            Ready to make an impact today? Let's guide the team and keep things moving!
                        @elseif(auth()->user()->isNFO())
                            Thank you for keeping everything running smoothly! Ready to solve today's challenges?
                        @else
                            We’re glad to have you here. Have a productive and wonderful day ahead!
                        @endif
                    </div>

                    <!-- Statistics -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-12 mb-4">
                            <div class="row g-4">
                                @if(!auth()->user()->isVM() && !auth()->user()->isNFO())
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
                                @endif
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
                                    <a href="{{ route('complaints.index', ['status' => [$completedStatusId ?? '', $closedStatusId ?? '']]) }}" class="card-link-stretched text-decoration-none">
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
                                @php
                                    $pendingStatusIds = \App\Models\Status::whereNotIn('id', array_filter([$completedStatusId ?? null, $closedStatusId ?? null]))
                                        ->pluck('id')
                                        ->toArray();
                                @endphp

                                <div class="col-md-3">
                                    <a href="{{ route('complaints.index', ['by' => auth()->user()->id, 'status' => $pendingStatusIds]) }}" class="card-link-stretched text-decoration-none">
                                        <div class="card shadow-lg border-0 rounded-4 bg-info text-white h-100 clickable-card">
                                            <div class="card-body text-center py-4">
                                                <h5 class="card-title mb-2">Pending with Me</h5>
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
                                    @include('complaints.partials.table', ['complaints' => $todayTickets, 'tableId' => 'todayComplaintsTable', 'showPerPage' => false])
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Previous Pending Tickets Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-lg border-0 rounded-4">
                                <div class="card-header text-white rounded-top-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #6c757d 0%, #495057 100%);">
                                    <h4 class="mb-0">Previous Pending Tickets</h4>
                                    <span class="badge bg-light text-secondary fs-6">{{ $previousTickets->count() }} Pending</span>
                                </div>
                                <div class="card-body">
                                    @include('complaints.partials.table', ['complaints' => $previousTickets, 'tableId' => 'previousComplaintsTable', 'showPerPage' => false])
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('complaints.partials.global-modals')

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

<script>
    $(document).ready(function() {
        $('#todayComplaintsTable, #previousComplaintsTable').DataTable({
            paging: false,
            info: false,
            searching: true,
            responsive: false,
            scrollX: true
        });
    });
</script>
@endsection

@stack('scripts')