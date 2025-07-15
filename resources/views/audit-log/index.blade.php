@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Audit Log</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle" id="auditLogTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Model</th>
                            <th>Model ID</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>
                                @if($log->properties['user_full_name'] ?? null)
                                    <span>{{ $log->properties['user_full_name'] }}</span>
                                    @php
                                        $eventDesc = strtolower($log->event ? $log->event : $log->description);
                                    @endphp
                                    @if(!str_contains($eventDesc, 'logged in') && !str_contains($eventDesc, 'logged out') && $log->causer && $log->properties['user_full_name'] !== $log->causer->name)
                                        <br><small class="text-muted">(Edited by: {{ $log->causer->name }})</small>
                                    @endif
                                @else
                                    @if($log->causer)
                                        <span>{{ $log->causer->name }}</span>
                                    @else
                                        System
                                    @endif
                                @endif
                            </td>
                            <td>
                                @php
                                    $event = strtolower($log->event ?? $log->description);
                                    $badgeClass = match(true) {
                                        str_contains($event, 'created') => 'success',
                                        str_contains($event, 'updated') => 'primary',
                                        str_contains($event, 'deleted') => 'danger',
                                        str_contains($event, 'login')   => 'purple',
                                        str_contains($event, 'logout')  => 'warning',
                                        default => 'info',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} text-uppercase">
                                    <i class="fa fa-{{
                                        str_contains($event, 'created') ? 'plus-circle' :
                                        (str_contains($event, 'updated') ? 'edit' :
                                        (str_contains($event, 'deleted') ? 'trash' :
                                        (str_contains($event, 'login') ? 'sign-in-alt' :
                                        (str_contains($event, 'logout') ? 'sign-out-alt' : 'info-circle'))))
                                    }} me-1"></i>
                                    {{ $log->event ? ucfirst($log->event) : $log->description }}
                                </span>
                            </td>
                            <td>
                                <i class="fa fa-database text-muted me-1"></i>
                                {{ class_basename($log->subject_type) }}
                            </td>
                            <td>{{ $log->subject_id }}</td>
                            <td>
                                @if($log->event == 'updated' && ($log->properties['old'] ?? null) && ($log->properties['attributes'] ?? null))
                                    @php
                                        $old = $log->properties['old'];
                                        $new = $log->properties['attributes'];
                                        $changed = collect($new)->filter(function($value, $key) use ($old) {
                                            return !array_key_exists($key, $old) || $old[$key] != $value;
                                        });
                                    @endphp
                                    <ul class="list-unstyled mb-0">
                                    @foreach($changed as $key => $value)
                                        @php
                                            $displayValue = isset($fieldMaps[$key]) ? ($fieldMaps[$key][$value] ?? $value) : $value;
                                        @endphp
                                        <li><strong>{{ $key }}:</strong> <span class="text-danger bg-light px-1 rounded">{{ $displayValue }}</span></li>
                                    @endforeach
                                    </ul>
                                @elseif($log->properties['old'] ?? null)
                                    <ul class="list-unstyled mb-0">
                                    @foreach($log->properties['old'] as $key => $value)
                                        <li><strong>{{ $key }}:</strong> <span class="text-danger bg-light px-1 rounded">{{ $value }}</span></li>
                                    @endforeach
                                    </ul>
                                @elseif($log->properties['old_vertical_ids'] ?? null)
                                    <span class="text-danger bg-light px-1 rounded">
                                        {{ collect($log->properties['old_vertical_ids'])->map(fn($id) => $verticalMap[$id] ?? $id)->implode(', ') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($log->event == 'updated' && ($log->properties['old'] ?? null) && ($log->properties['attributes'] ?? null))
                                    @php
                                        $old = $log->properties['old'];
                                        $new = $log->properties['attributes'];
                                        $changed = collect($new)->filter(function($value, $key) use ($old) {
                                            return !array_key_exists($key, $old) || $old[$key] != $value;
                                        });
                                    @endphp
                                    <ul class="list-unstyled mb-0">
                                    @foreach($changed as $key => $value)
                                        @php
                                            $displayValue = isset($fieldMaps[$key]) ? ($fieldMaps[$key][$value] ?? $value) : $value;
                                        @endphp
                                        <li><strong>{{ $key }}:</strong> <span class="text-success bg-light px-1 rounded">{{ $displayValue }}</span></li>
                                    @endforeach
                                    </ul>
                                @elseif($log->properties['attributes'] ?? null)
                                    <ul class="list-unstyled mb-0">
                                    @foreach($log->properties['attributes'] as $key => $value)
                                        <li><strong>{{ $key }}:</strong> <span class="text-success bg-light px-1 rounded">{{ $value }}</span></li>
                                    @endforeach
                                    </ul>
                                @elseif($log->properties['new_vertical_ids'] ?? null)
                                    <span class="text-success bg-light px-1 rounded">
                                        {{ collect($log->properties['new_vertical_ids'])->map(fn($id) => $verticalMap[$id] ?? $id)->implode(', ') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span data-bs-toggle="tooltip" title="{{ $log->properties['user_agent'] ?? '' }}">
                                    {{ $log->properties['ip_address'] ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    $('#auditLogTable').DataTable({
        responsive: true,
        order: [[0, "desc"]],
        dom: '<"d-flex justify-content-between align-items-center mb-2"Bfl>rtip',
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fa fa-copy"></i> Copy',
                className: 'btn btn-light btn-sm me-1',
                titleAttr: 'Copy'
            },
            {
                extend: 'csv',
                text: '<i class="fa fa-file-csv"></i> CSV',
                className: 'btn btn-light btn-sm me-1',
                titleAttr: 'Export as CSV'
            },
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel"></i> Excel',
                className: 'btn btn-light btn-sm me-1',
                titleAttr: 'Export as Excel'
            },
            {
                extend: 'pdf',
                text: '<i class="fa fa-file-pdf"></i> PDF',
                className: 'btn btn-light btn-sm me-1',
                titleAttr: 'Export as PDF'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Print',
                className: 'btn btn-light btn-sm',
                titleAttr: 'Print'
            },
            {
                extend: 'colvis',
                text: '<i class="fa fa-columns"></i> Columns',
                className: 'btn btn-light btn-sm',
                titleAttr: 'Column Visibility'
            }
        ]
    });
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
<style>
.avatar-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #6c63ff;
    color: #fff;
    font-weight: bold;
    font-size: 1rem;
}
.badge.bg-purple {
    background-color: #6f42c1 !important;
    color: #fff;
}
</style>
@endpush 