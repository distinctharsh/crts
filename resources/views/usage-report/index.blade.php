@extends('layouts.app')

@section('content')
<div class="container-fluid" id="printableArea">
    <div class="row mb-4  print-hide">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Usage Report</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Usage Report</li>
                    </ol>
                </div>
                <div class="page-title-right print-hide">
                    <button onclick="printReport()" class="btn btn-danger shadow-sm">
                        <i class="fas fa-print me-1"></i> Print / Save PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Picker -->
    <div class="row mb-4  print-hide">
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
                            <div class="col-md-3 d-flex align-items-end print-hide">
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
                        <table id="categoryReportTable" class="table table-hover table-bordered align-middle mb-0 printable-table">
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
                                        <td>
                                            @if(isset($category['full_path']) && $category['full_path'] != $category['name'])
                                                <span class="text-center">{{ $category['full_path'] }}</span>
                                            @else
                                                {{ $category['name'] }}
                                            @endif
                                        </td>
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

    <!-- Main Parent Category-wise Statistics (Aggregated) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Main Category Aggregated Statistics</h5>
                    <span class="badge bg-light text-dark font-weight-normal border">Excludes disabled categories</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive-print">
                        <table id="parentCategoryReportTable" class="table table-hover table-bordered align-middle mb-0 printable-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th>Main Category Name</th>
                                    <th class="text-center" width="15%">Pending</th>
                                    <th class="text-center" width="15%">Completed</th>
                                    <th class="text-center" width="15%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $parentSn = 1; @endphp
                                @forelse($parentCategoryReportData as $pCategory)
                                    @php
                                        if ($pCategory['total'] == 0) continue;
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold">{{ $parentSn++ }}</td>
                                        <td class="font-weight-bold">{{ $pCategory['name'] }}</td>
                                        <td class="text-center">{{ $pCategory['pending'] }}</td>
                                        <td class="text-center">{{ $pCategory['completed'] }}</td>
                                        <td class="text-center">{{ $pCategory['total'] }}</td>
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
                        <table id="usageReportTable" class="table table-hover table-bordered align-middle mb-0 printable-table">
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
    var tables = $.fn.dataTable.fnTables(true);
    $(tables).each(function() {
        $(this).DataTable().page.len(-1).draw();
    });

    var dateFromVal = $('#date_from').val();
    var dateToVal = $('#date_to').val();
    
    function formatDate(dateStr) {
        if (!dateStr) return '';
        var parts = dateStr.split('-');
        return parts.length === 3 ? parts[2] + '/' + parts[1] + '/' + parts[0] : dateStr;
    }

    var formattedFrom = formatDate(dateFromVal);
    var formattedTo = formatDate(dateToVal);

    // Dynamic Header HTML Construction
    var printHeaderHtml = '<div class="print-header-center">';
    printHeaderHtml += '<h2 class="print-title">Usage Report</h2>';
    
    if (formattedFrom || formattedTo) {
        printHeaderHtml += '<p class="print-dates">';
        if (formattedFrom) printHeaderHtml += '' + formattedFrom;
        if (formattedFrom && formattedTo) printHeaderHtml += ' - ';
        if (formattedTo) printHeaderHtml += ' - ' + formattedTo;
        printHeaderHtml += '</p>';
    }
    
    printHeaderHtml += '<hr class="print-divider">';
    printHeaderHtml += '</div>';

    setTimeout(function() {
        var printContents = document.getElementById('printableArea').innerHTML;
        var printWindow = window.open('', '_blank', 'width=1100,height=800');
        
        printWindow.document.write('<html><head><title>Usage Reports</title>');
        
        $('link[rel="stylesheet"]').each(function() {
            printWindow.document.write('<link rel="stylesheet" href="' + $(this).attr('href') + '">');
        });

        printWindow.document.write(`
            <style>
                * {
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
                body { padding: 15px; background: #fff; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
                
                /* Center Top Header Styling */
                .print-header-center {
                    text-align: center !important;
                    margin-bottom: 20px !important;
                }
                .print-title {
                    font-size: 22px !important;
                    font-weight: bold !important;
                    margin-bottom: 5px !important;
                    color: #000 !important;
                    text-transform: uppercase;
                }
                .print-dates {
                    font-size: 13px !important;
                    color: #333 !important;
                    font-weight: 600 !important;
                    margin: 0 !important;
                }
                .print-divider {
                    margin: 10px 0 20px 0 !important;
                    border: 0;
                    border-top: 1px solid #ccc !important;
                }
                .print-hide, 
                #dateRangeForm,
                .page-title-box,
                .dataTables_filter, 
                .dataTables_length, 
                .dataTables_paginate, 
                .dataTables_info, 
                .dt-buttons { 
                    display: none !important; 
                }
                .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; margin-bottom: 20px !important; }
                .card-header { background-color: #f8f9fa !important; border-bottom: 1px solid #dee2e6 !important; }
                table { width: 100% !important; border-collapse: collapse !important; }
                th, td { border: 1px solid #dee2e6 !important; padding: 8px 10px !important; font-size: 12px; }
                tr { page-break-inside: avoid !important; break-inside: avoid !important; }
                thead { display: table-header-group !important; }
                .progress { background-color: #e9ecef !important; height: 8px !important; border: 1px solid #ccc !important; display: block !important; overflow: hidden !important; }
                .progress-bar { height: 100% !important; display: block !important; }
                .bg-success { background-color: #198754 !important; }
                .bg-warning { background-color: #ffc107 !important; }
                .bg-danger { background-color: #dc3545 !important; }

                @page { size: A4 portrait; margin: 0mm; }
            </style>
        `);
        
        printWindow.document.write('</head><body>');
        printWindow.document.write(printHeaderHtml);
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        setTimeout(function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
            $(tables).each(function() {
                $(this).DataTable().page.len(25).draw();
            });
        }, 500);

    }, 300);
}

$(function () {
    $('.printable-table').DataTable({
        "paging": true,
        "pageLength": 25,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [{ extend: 'excel', text: '<i class="fa fa-file-excel"></i> Excel', className: 'btn btn-light btn-sm me-1' }]
    });
});
</script>
@endpush