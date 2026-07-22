@extends('layouts.app')

@section('content')
<div class="container-fluid" id="printableArea">
    <div class="row mb-4 print-hide">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Usage Report</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Usage Report</li>
                    </ol>
                </div>
                <div class="page-title-right">
                    <button onclick="printReport()" class="btn btn-danger shadow-sm">
                        <i class="fas fa-print me-1"></i> Print / Save PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Picker -->
    <div class="row mb-4 print-hide">
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

    <div class="d-none print-show mb-3">
        <h3 style="text-align: center; margin-bottom: 5px;">Usage Report</h3>
        <p style="text-align: center; font-size: 12px; color: #666; margin: 0;">Generated Date: {{ date('d-M-Y H:i A') }}</p>
        <hr style="margin: 10px 0;">
    </div>

    <!-- Summary Cards -->
    @php
        $totalPending = collect($reportData)->sum('pending');
        $totalCompleted = collect($reportData)->sum('completed');
        $totalTasks = collect($reportData)->sum('total');
        $avgCompletionRate = $totalTasks > 0 ? round(($totalCompleted / $totalTasks) * 100, 2) : 0;
    @endphp

    <div class="row mb-4">
        <div class="col-3 mb-3">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Assigned Tasks</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTasks }}</div>
                </div>
            </div>
        </div>

        <div class="col-3 mb-3">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed & Closed</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCompleted }}</div>
                </div>
            </div>
        </div>

        <div class="col-3 mb-3">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Tasks</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPending }}</div>
                </div>
            </div>
        </div>

        <div class="col-3 mb-3">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Completion Rate</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $avgCompletionRate }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category-wise Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Category-wise Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive-print">
                        <table id="categoryReportTable" class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th>Category Name</th>
                                    <th class="text-center" width="15%">Pending</th>
                                    <th class="text-center" width="15%">Completed</th>
                                    <th class="text-center" width="15%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $catSn = 1; @endphp
                                @forelse($categoryReportData as $category)
                                    @php
                                        if ($category['total'] == 0) continue;
                                        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category['level']);
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold">{{ $catSn++ }}</td>
                                        <td>{!! $indent !!}{{ $category['name'] }}</td>
                                        <td class="text-center">{{ $category['pending'] }}</td>
                                        <td class="text-center">{{ $category['completed'] }}</td>
                                        <td class="text-center">{{ $category['total'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                    <div class="table-responsive-print">
                        <table id="usageReportTable" class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th>User Name</th>
                                    <th class="text-center" width="15%">Pending</th>
                                    <th class="text-center" width="15%">Completed</th>
                                    <th class="text-center" width="15%">Total</th>
                                    <th class="text-center" width="20%">Completion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sn = 1; @endphp
                                @forelse($reportData as $user)
                                    @php
                                        if ($user['total'] == 0) continue;
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold">{{ $sn++ }}</td>
                                        <td>{{ $user['name'] }}</td>
                                        <td class="text-center">{{ $user['pending'] }}</td>
                                        <td class="text-center">{{ $user['completed'] }}</td>
                                        <td class="text-center">{{ $user['total'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-info text-info fs-6">{{ $user['completion_rate'] }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No data available</td>
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
function printReport() {
    var usageTable = $('#usageReportTable').DataTable();
    var catTable = $('#categoryReportTable').DataTable();
    usageTable.page.len(-1).draw();
    catTable.page.len(-1).draw();

    setTimeout(function() {
        var printContents = document.getElementById('printableArea').innerHTML;
        var printWindow = window.open('', '_blank', 'width=1000,height=800');
        
        printWindow.document.write('<html><head><title>Usage Report</title>');
        $('link[rel="stylesheet"]').each(function() {
            printWindow.document.write('<link rel="stylesheet" href="' + $(this).attr('href') + '">');
        });
        printWindow.document.write(`
            <style>
                body { padding: 15px; background: #fff; font-family: Arial, sans-serif; }
                .print-hide, .dataTables_filter, .dataTables_length, .dataTables_paginate, .dataTables_info, .dt-buttons { display: none !important; }
                .print-show { display: block !important; }
                .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 15px !important; }
                table { width: 100% !important; border-collapse: collapse !important; }
                th, td { border: 1px solid #dee2e6 !important; padding: 6px 8px !important; text-align: left; font-size: 12px; }
                tr { page-break-inside: avoid !important; break-inside: avoid !important; }
                thead { display: table-header-group !important; }
                @page { size: A4 portrait; margin: 10mm; }
            </style>
        `);
        
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        setTimeout(function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
            usageTable.page.len(25).draw();
            catTable.page.len(25).draw();
        }, 500);

    }, 300);
}

$(function () {
    $('#usageReportTable').DataTable({
        "paging": true,
        "pageLength": 25,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [{ extend: 'excel', text: '<i class="fa fa-file-excel"></i> Excel', className: 'btn btn-light btn-sm me-1' }]
    });
    $('#categoryReportTable').DataTable({
        "paging": true,
        "pageLength": 25,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [{ extend: 'excel', text: '<i class="fa fa-file-excel"></i> Excel', className: 'btn btn-light btn-sm me-1' }]
    });
});
</script>
@endpush
