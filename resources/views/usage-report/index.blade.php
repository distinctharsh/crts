@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Usage Report</h3>
                </div>
                <div class="card-body">
                    <div style="width: 100%; overflow-x: auto;">
                        <table id="usageReportTable" class="table table-bordered table-striped" style="width: 100%; margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Pending</th>
                                    <th>Completed</th>
                                    <th>Total</th>
                                    <th>Completion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user['name'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">{{ $user['pending'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $user['completed'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $user['total'] }}</span>
                                    </td>
                                    <td>
                                        <div class="progress progress-sm" style="min-width: 100px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $user['completion_rate'] }}%">
                                                {{ $user['completion_rate'] }}%
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
$(function () {
    $('#usageReportTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pageLength": 25
    });
});
</script>
@endpush

@push('styles')
<style>
    .progress {
        height: 25px;
        border-radius: 4px;
        min-width: 100px;
        margin-bottom: 0;
    }

    .progress-bar {
        line-height: 25px;
        font-weight: bold;
    }

    .badge {
        font-size: 0.9em;
        padding: 5px 10px;
    }
</style>
@endpush