@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Tickets', 'url' => route('complaints.index')],
];
@endphp

<style>
    div.dataTables_wrapper div.dataTables_filter input { width: 400px; }
    .ts-wrapper.multi .ts-control {
        max-height: 38px !important; min-height: 38px !important; height: 38px !important;
        overflow: hidden !important; display: flex !important; align-items: center !important;
        padding: 4px 12px !important; white-space: nowrap !important; background-color: #fff !important;
        border-radius: 0.375rem !important;
    }
    .ts-wrapper.multi .ts-control .item { display: none !important; }
    .ts-count-badge {
        background-color: #0d6efd; color: white; font-size: 0.75rem; font-weight: 600;
        padding: 3px 10px; border-radius: 50rem; margin-right: 6px; display: inline-flex;
        align-items: center; flex-shrink: 0;
    }
    .ts-dropdown .option { display: flex !important; align-items: center !important; padding: 8px 12px !important; }
    .ts-dropdown .option input[type="checkbox"] { margin-right: 10px !important; width: 16px; height: 16px; cursor: pointer; }
    .ts-wrapper.multi.has-items:not(.focus) .ts-control input::placeholder { color: transparent !important; }
    .ts-wrapper.multi .ts-control input {
        display: inline-block !important; opacity: 1 !important; position: relative !important;
        visibility: visible !important; width: auto !important; min-width: 60px !important;
        flex-grow: 1 !important; background: transparent !important; border: none !important;
        outline: none !important; box-shadow: none !important;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 mt-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);">
                    <h4 class="mb-0">Ticket History</h4>
                    <div>
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
                                aria-expanded="{{ (request('status') || request('by') || request('vertical') || request('networktype') || request('section') || request('date_from') || request('date_to') || request('search')) ? 'true' : 'false' }}"
                                aria-controls="filterCollapse">
                                <strong class="text-dark">Filter Tickets</strong>
                                <span class="float-end bg-secondary" style="padding: 5px 10px; border-radius: 8px;">
                                    <i class="bi {{ (request('status') || request('by') || request('vertical') || request('networktype') || request('section') || request('date_from') || request('date_to') || request('search')) ? 'bi-chevron-up' : 'bi-chevron-down' }}" id="filterChevron"></i>
                                </span>
                            </div>
                            <div class="collapse{{ (request('status') || request('by') || request('vertical') || request('networktype') || request('section') || request('date_from') || request('date_to') || request('search')) ? ' show' : '' }}" id="filterCollapse">
                                <div class="card-body py-3">
                                    <form method="GET" action="{{ route('complaints.index') }}" id="filterForm">
                                        <input type="hidden" name="per_page" id="filterFormPerPage" value="{{ request('per_page', 10) }}">
                                        <input type="hidden" name="search" id="filterFormSearch" value="{{ request('search') }}">
                                        
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-2">
                                                <label class="form-label mb-1"><i class="bi bi-flag me-1"></i>Status</label>
                                                <select name="status[]" class="form-select tom-select" multiple>
                                                    <option value="">All Status</option>
                                                    @foreach($statuses as $status)
                                                    <option value="{{ $status->id }}" {{ in_array($status->id, is_array(request('status')) ? request('status') : explode(',', request('status', ''))) ? 'selected' : '' }}>
                                                        {{ $status->display_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1"><i class="bi bi-person-badge me-1"></i>Assigned To</label>
                                                <select name="by[]" class="form-select tom-select" multiple>
                                                    <option value="">Assigned To</option>
                                                    @foreach($usersList as $user)
                                                    <option value="{{ $user->id }}" {{ in_array($user->id, is_array(request('by')) ? request('by') : explode(',', request('by', ''))) ? 'selected' : '' }}>
                                                        {{ $user->full_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1"><i class="bi bi-grid me-1"></i>Category</label>
                                                <select name="vertical_id[]" class="form-select tom-select" multiple>
                                                    <option value="">All Categories</option>
                                                    @foreach($verticals as $vertical)
                                                        <option value="{{ $vertical->id }}" {{ in_array($vertical->id, is_array(request('vertical_id')) ? request('vertical_id') : explode(',', request('vertical_id', ''))) ? 'selected' : '' }}>
                                                            {{ $vertical->full_path ?? $vertical->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1"><i class="bi bi-diagram-3 me-1"></i>Network Type</label>
                                                <select name="networktype[]" class="form-select tom-select" multiple>
                                                    <option value="">All Issue Type</option>
                                                    @foreach($networkTypes as $networktype)
                                                    <option value="{{ $networktype->id }}" {{ in_array($networktype->id, is_array(request('networktype')) ? request('networktype') : explode(',', request('networktype', ''))) ? 'selected' : '' }}>
                                                        {{ $networktype->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1"><i class="bi bi-card-checklist me-1"></i>Request Type</label>
                                                <select name="request_type_id[]" class="form-select tom-select" multiple>
                                                    <option value="">All Request Type</option>
                                                    @foreach($requestTypes as $requestType)
                                                        <option value="{{ $requestType->id }}" {{ in_array($requestType->id, is_array(request('request_type_id')) ? request('request_type_id') : explode(',', request('request_type_id', ''))) ? 'selected' : '' }}>
                                                            {{ $requestType->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1"><i class="bi bi-building me-1"></i>Section</label>
                                                <select name="section[]" class="form-select tom-select" multiple>
                                                    <option value="">All Section</option>
                                                    @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" {{ in_array($section->id, is_array(request('section')) ? request('section') : explode(',', request('section', ''))) ? 'selected' : '' }}>
                                                        {{ $section->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label class="form-label fw-semibold text-muted small mb-1">
                                                    <i class="bi bi-calendar-event me-1"></i>From Date
                                                </label>
                                                <input type="text" name="date_from" id="date_from" class="form-control date-picker" value="{{ request('date_from') }}" placeholder="dd/mm/yyyy">
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label class="form-label fw-semibold text-muted small mb-1">
                                                    <i class="bi bi-calendar-check me-1"></i>To Date
                                                </label>
                                                <input type="text" name="date_to" id="date_to" class="form-control date-picker" value="{{ request('date_to') }}" placeholder="dd/mm/yyyy">
                                            </div>
                                            
                                            <input type="hidden" name="quick_filter" id="quick_filter" value="{{ request('quick_filter') }}">

                                            <div class="col-lg-3 col-md-6">
                                                <label class="form-label fw-semibold text-muted small mb-2">
                                                    <i class="bi bi-clock-history me-1"></i>Quick Time Filter
                                                </label>
                                                <select id="quickTimeFilter" class="form-select form-select-sm">
                                                    <option value="">Select time range...</option>
                                                    <optgroup label="Presets">
                                                        <option value="1h" {{ request('quick_filter') == '1h' ? 'selected' : '' }}>Pending > 1 Hour</option>
                                                        <option value="2h" {{ request('quick_filter') == '2h' ? 'selected' : '' }}>Pending > 2 Hours</option>
                                                        <option value="3h" {{ request('quick_filter') == '3h' ? 'selected' : '' }}>Pending > 3 Hours</option>
                                                        <option value="4h" {{ request('quick_filter') == '4h' ? 'selected' : '' }}>Pending > 4 Hours</option>
                                                    </optgroup>
                                                    <option value="custom" style="font-weight: bold; color: #0d6efd;" {{ str_contains(request('quick_filter', ''), 'custom') ? 'selected' : '' }}>+ Custom (Type N Hours/Days/etc.)</option>
                                                    <option value="clear">Clear Quick Filter</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6 d-flex gap-2 ms-auto">
                                                <a href="{{ route('complaints.index') }}" class="btn btn-outline-danger rounded-pill px-3 w-100 text-center text-nowrap">
                                                    <i class="bi bi-x-circle me-1"></i> Reset
                                                </a>
                                                <button class="btn btn-secondary rounded-pill px-3 w-100 text-nowrap" type="submit">
                                                    Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="complaintsTableContainer">
                        @include('complaints.partials.table', ['complaints' => $complaints, 'tableId' => 'complaintsTable'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('complaints.partials.global-modals')

@endsection

@push('scripts')
<script>
    let tomSelectInitialized = false;

    function updateCountBadge(ts) {
        let control = ts.control;
        let count = ts.items.length;
        let existingBadge = control.querySelector('.ts-count-badge');
        if (existingBadge) existingBadge.remove();
        if (count > 0) {
            let badge = document.createElement('span');
            badge.className = 'ts-count-badge';
            badge.textContent = count + ' selected';
            let inputField = control.querySelector('input');
            if (inputField) control.insertBefore(badge, inputField);
            else control.appendChild(badge);
        }
    }

    function initTomSelects() {
        if (tomSelectInitialized) return;
        
        document.querySelectorAll('select.tom-select').forEach(function(el) {
            let config = { create: false, sortField: { field: 'text', direction: 'asc' } };
            if(['status[]', 'by[]', 'vertical_id[]', 'networktype[]', 'section[]', 'request_type_id[]'].includes(el.name)){
                config.maxItems = null;
                config.plugins = { checkbox_options: {} };
                config.hideSelected = false;
                config.onItemAdd = function() { this.setTextboxValue(''); this.refreshOptions(false); updateCountBadge(this); };
                config.onItemRemove = function() { updateCountBadge(this); };
                config.onInitialize = function() { updateCountBadge(this); };
            }
            new TomSelect(el, config);
        });
        tomSelectInitialized = true;
    }

    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#complaintsTable')) {
            $('#complaintsTable').DataTable().destroy();
        }
        let commonExportOptions = { columns: ':not(:last-child)' };

        let table = $('#complaintsTable').DataTable({
            responsive: false,
            scrollX: true,
            order: [],
            paging: false,
            info: false,
            language: { 
                search: "", 
                searchPlaceholder: "Search complaints across all pages...",
                zeroRecords: "No complaints found"
            },
            dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt',
            buttons: [
                { extend: 'copy', text: '<i class="bi bi-clipboard"></i>', className: 'btn btn-light btn-sm me-1', exportOptions: commonExportOptions },
                { extend: 'csv', text: '<i class="bi bi-filetype-csv"></i>', className: 'btn btn-light btn-sm me-1', exportOptions: commonExportOptions },
                { extend: 'excel', text: '<i class="bi bi-file-earmark-excel"></i>', className: 'btn btn-light btn-sm me-1', exportOptions: commonExportOptions },
                { extend: 'pdf', text: '<i class="bi bi-file-earmark-pdf"></i>', className: 'btn btn-light btn-sm me-1', exportOptions: commonExportOptions },
                { extend: 'print', text: '<i class="bi bi-printer"></i>', className: 'btn btn-light btn-sm', exportOptions: commonExportOptions }
            ],
            columnDefs: [{ orderable: false, targets: 0 }]
        });

        let $searchInput = $('div.dataTables_filter input');
        $searchInput.off('keyup.DT search.DT input.DT paste.DT cut.DT');

        let currentSearchVal = $('#filterFormSearch').val();
        if (currentSearchVal) {
            $searchInput.val(currentSearchVal);
            $searchInput.focus();
            let valLen = currentSearchVal.length;
            if ($searchInput[0] && $searchInput[0].setSelectionRange) {
                $searchInput[0].setSelectionRange(valLen, valLen);
            }
        }

        let searchTimeout = null;
        $searchInput.on('keyup input', function(e) {
            let keyword = $(this).val();
            $('#filterFormSearch').val(keyword);
            $('#complaintsTableContainer').css('opacity', '0.6');

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                let formData = $('#filterForm').serialize();
                let actionUrl = $('#filterForm').attr('action') + '?' + formData;
                fetchTableData(actionUrl);
            }, 500);
        });
    }

    function fetchTableData(targetUrl) {
        $('#complaintsTableContainer').css('opacity', '0.5');
        $.ajax({
            url: targetUrl,
            type: 'GET',
            success: function(response) {
                $('#complaintsTableContainer').html($(response).find('#complaintsTableContainer').html());
                $('#complaintsTableContainer').css('opacity', '1');
                initDataTable();
            },
            error: function() {
                $('#complaintsTableContainer').css('opacity', '1');
            }
        });
    }

    $(document).ready(function() {
        // Fast Initial Table Load
        initDataTable();

        // Lazy Load TomSelect
        var isAnyFilterSet = {{ (request('status') || request('by') || request('vertical_id') || request('networktype') || request('section') || request('request_type_id') || request('date_from') || request('date_to') || request('search')) ? 'true' : 'false' }};
        if (isAnyFilterSet) {
            initTomSelects();
        }

        // Collapse Event Listeners
        var filterCollapse = document.getElementById('filterCollapse');
        var chevron = document.getElementById('filterChevron');

        if (filterCollapse) {
            filterCollapse.addEventListener('show.bs.collapse', function() {
                initTomSelects();
                localStorage.setItem('complaintsFilterOpen', 'open');
                if(chevron) { chevron.classList.remove('bi-chevron-down'); chevron.classList.add('bi-chevron-up'); }
            });
            filterCollapse.addEventListener('hide.bs.collapse', function() {
                localStorage.setItem('complaintsFilterOpen', 'closed');
                if(chevron) { chevron.classList.remove('bi-chevron-up'); chevron.classList.add('bi-chevron-down'); }
            });
        }

        // Handle AJAX Pagination
        $(document).on('click', '#complaintsTableContainer .pagination a', function(e) {
            e.preventDefault();
            let targetUrl = $(this).attr('href');
            if (targetUrl) fetchTableData(targetUrl);
        });

        // Top-Right Per Page
        $(document).on('change', '#perPageSelect', function() {
            let val = $(this).val();
            $('#filterFormPerPage').val(val);
            let formData = $('#filterForm').serialize();
            let actionUrl = $('#filterForm').attr('action') + '?' + formData;
            fetchTableData(actionUrl);
        });

        // Quick Time Filter
        $(document).on('change', '#quickTimeFilter', function() {
            let value = $(this).val();
            if (value === '' || value === 'clear') {
                $('#quick_filter').val('');
                $(this).val('');
                $('#filterForm').submit();
                return;
            }
            if (value === 'custom') {
                $('#customTimeInput').val('');
                $('#customTimeFilterModal').modal('show');
                return;
            }
            $('#quick_filter').val(value);
            $('#filterForm').submit();
        });

        $(document).on('click', '#applyCustomTime', function() {
            const customValue = $('#customTimeInput').val().trim().toLowerCase();
            if (!customValue) { $('#customTimeInput').addClass('is-invalid'); return; }
            const amount = parseInt(customValue);
            const unit = customValue.replace(/[0-9]/g, '');
            if (isNaN(amount) || !['h', 'd', 'w', 'm', 'y'].includes(unit)) {
                $('#customTimeInput').addClass('is-invalid');
                return;
            }
            $('#customTimeInput').removeClass('is-invalid');
            $('#customTimeFilterModal').modal('hide');
            $('#quick_filter').val(customValue);
            $('#filterForm').submit();
        });

        $(document).on('input', '#customTimeInput', function() {
            $(this).removeClass('is-invalid');
        });

        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            let actionUrl = $(this).attr('action') + '?' + $(this).serialize();
            fetchTableData(actionUrl);
        });
    });
</script>
@endpush