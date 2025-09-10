@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Usage Report</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Usage Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Picker -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('usage-report.index') }}" method="GET" id="dateRangeForm">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" 
                                       value="{{ $dateFrom ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" 
                                       value="{{ $dateTo ?? '' }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group gap-2" role="group">
                                    <button type="submit" class="btn btn-primary mt-4">
                                        <i class="fas fa-filter me-1"></i> Apply Filter
                                    </button>
                                    @if(request()->has('date_from') || request()->has('date_to'))
                                    <a href="{{ route('usage-report.index') }}" class="btn btn-outline-secondary mt-4">
                                        <i class="fas fa-times me-1"></i> Clear
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @php
        $totalPending = collect($reportData)->sum('pending');
        $totalCompleted = collect($reportData)->sum('completed');
        $totalTasks = collect($reportData)->sum('total');
        $avgCompletionRate = $totalTasks > 0 ? round(($totalCompleted / $totalTasks) * 100, 2) : 0;
    @endphp

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Tasks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Tasks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCompleted }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Tasks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPending }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Average Completion Rate</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $avgCompletionRate }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" style="width: {{ $avgCompletionRate }}%" 
                                             role="progressbar" aria-valuenow="{{ $avgCompletionRate }}" 
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">User Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="usageReportTable" class="table table-hover table-bordered table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th>User Name</th>
                                    <th class="text-center">Pending</th>
                                    <th class="text-center">Completed</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Completion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $index => $user)
                                @php
                                    $performanceClass = '';
                                    if ($user['completion_rate'] >= 80) {
                                        $performanceClass = 'bg-success bg-opacity-10';
                                    } elseif ($user['completion_rate'] >= 50) {
                                        $performanceClass = 'bg-warning bg-opacity-10';
                                    } else {
                                        $performanceClass = 'bg-danger bg-opacity-10';
                                    }
                                @endphp
                                <tr class="{{ $performanceClass }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $user['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-warning bg-opacity-25 text-warning p-2">
                                            <i class="fas fa-clock me-1"></i> {{ $user['pending'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-success bg-opacity-25 text-success p-2">
                                            <i class="fas fa-check-circle me-1"></i> {{ $user['completed'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-primary bg-opacity-25 text-primary p-2">
                                            <i class="fas fa-tasks me-1"></i> {{ $user['total'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $user['completion_rate'] >= 50 ? 'success' : ($user['completion_rate'] >= 30 ? 'warning' : 'danger') }}" 
                                                     role="progressbar" style="width: {{ $user['completion_rate'] }}%" 
                                                     aria-valuenow="{{ $user['completion_rate'] }}" 
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="text-nowrap">{{ $user['completion_rate'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                            <h5 class="text-muted">No data available</h5>
                                            <p class="text-muted mb-0">Try adjusting your filters or check back later.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
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
$(function () {
    // Initialize DataTable with export buttons
    var table = $('#usageReportTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pageLength": 25,
        "serverSide": false,
        "processing": false,
        "dom": 'Bfrtip',
        "buttons": [
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel"></i> Excel',
                className: 'btn btn-light btn-sm me-1',
                titleAttr: 'Export as Excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            }
        ]
    });
    // Add the export buttons to the DOM
    table.buttons().container().appendTo('#usageReportTable_wrapper .col-md-6:eq(0)');
});
</script>
@endpush

@push('styles')
<style>
    /* Custom styles for the usage report */
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        margin-bottom: 1.5rem;
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.25rem;
    }
    
    .card-title {
        color: #4e73df;
        font-weight: 600;
        margin-bottom: 0;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        background-color: #f8f9fc;
        border-bottom-width: 1px;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    
    .progress {
        height: 6px;
        border-radius: 3px;
        background-color: #eaecf4;
    }
    
    .progress-bar {
        font-size: 0.65rem;
        line-height: 1.5;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        width: 32px;
        font-weight: 600;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .table-responsive {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
        }
    }

    .badge {
        font-size: 0.9em;
        padding: 5px 10px;
    }
</style>
@endpush