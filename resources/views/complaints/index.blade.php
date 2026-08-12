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

    .ts-wrapper.multi .ts-control {
        max-height: 38px !important;
        min-height: 38px !important;
        height: 38px !important;
        overflow: hidden !important;
        display: flex !important;
        align-items: center !important;
        padding: 4px 12px !important;
        white-space: nowrap !important;
        background-color: #fff !important;
        border-radius: 0.375rem !important;
    }

    .ts-wrapper.multi .ts-control .item {
        display: none !important;
    }

    .ts-count-badge {
        background-color: #0d6efd;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50rem;
        margin-right: 6px;
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
    }

    .ts-dropdown .option {
        display: flex !important;
        align-items: center !important;
        padding: 8px 12px !important;
    }

    .ts-dropdown .option input[type="checkbox"] {
        margin-right: 10px !important;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .ts-wrapper.multi.has-items:not(.focus) .ts-control input::placeholder {
        color: transparent !important;
    }

    .ts-wrapper.multi .ts-control input {
        display: inline-block !important;
        opacity: 1 !important;
        position: relative !important;
        visibility: visible !important;
        width: auto !important;
        min-width: 60px !important;
        flex-grow: 1 !important;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 mt-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);">
                    <h4 class="mb-0">Ticket History</h4>
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
                                    <form method="GET" action="{{ route('complaints.index') }}" id="filterForm">
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
                    <!-- End Filter Form -->
                    <div class="modal fade" id="customTimeFilterModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Custom Time Range</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="customTimeInput" class="form-label">Enter duration</label>
                                        <input type="text" id="customTimeInput" class="form-control" placeholder="e.g., 5h (Hours), 10d (Days), 3w (Weeks), 2m (Months), 1y (Years)">
                                        <small class="text-muted">Use numbers followed by h, d, w, m, or y</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="applyCustomTime" class="btn btn-primary">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="complaintsTableContainer">
                        @include('complaints.partials.table', ['complaints' => $complaints, 'tableId' => 'complaintsTable'])
                    </div>
                    <div id="complaintsModalsContainer">
                        @foreach($complaints as $complaint)
                            @include('complaints.partials.assign-modal', ['complaint' => $complaint])
                            @include('complaints.partials.revert-modal', ['complaint' => $complaint, 'managers' => $managers])
                            @include('complaints.partials.close-modal', ['complaint' => $complaint, 'closeStatus' => $closeStatus])
                            @include('complaints.partials.update-status-modal', ['complaint' => $complaint])
                        @endforeach
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
        $('#complaintsTable').DataTable({
            responsive: false,
            scrollX: true,
            order: [],
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
            ]
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
            let config = {
                create: false,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                }
            };

            // Enable multiple selection for all multi-select filters
            if(el.name === 'status[]' || el.name === 'by[]' || el.name === 'vertical_id[]' || el.name === 'networktype[]' || el.name === 'section[]' || el.name === 'request_type_id[]'){
                config.maxItems = null;
                config.plugins = {
                    checkbox_options: {}
                };
                config.hideSelected = false;

                config.onItemAdd = function() {
                    this.setTextboxValue('');
                    this.refreshOptions(false);
                    updateCountBadge(this);
                };
                config.onItemRemove = function() {
                    updateCountBadge(this);
                };
                config.onInitialize = function() {
                    updateCountBadge(this);
                };
            }

            function updateCountBadge(ts) {
                let control = ts.control;
                let count = ts.items.length;
                
                let existingBadge = control.querySelector('.ts-count-badge');
                if (existingBadge) {
                    existingBadge.remove();
                }

                if (count > 0) {
                    let badge = document.createElement('span');
                    badge.className = 'ts-count-badge';
                    badge.textContent = count + ' selected';
                    
                    let inputField = control.querySelector('input');
                    if (inputField) {
                        control.insertBefore(badge, inputField);
                    } else {
                        control.appendChild(badge);
                    }
                }
            }

            new TomSelect(el, config);
        });

        $('#quickTimeFilter').on('change', function() {
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
        
        $('#applyCustomTime').on('click', function() {
            const customValue = $('#customTimeInput').val().trim().toLowerCase();
            if (!customValue) {
                $('#customTimeInput').addClass('is-invalid');
                return;
            }
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
        
        $('#customTimeInput').on('input', function() {
            $(this).removeClass('is-invalid');
        });
        
        function applyTimeFilter(value) {
            const dateFromInput = $('#date_from');
            const dateToInput = $('#date_to');
            const today = new Date();
            const fromDate = new Date();
            const amount = parseInt(value);
            const unit = value.replace(/[0-9]/g, ''); // 'h', 'd', 'w', 'm', 'y'
            
            switch(unit) {
                case 'h': // Hours
                    fromDate.setHours(today.getHours() - amount);
                    break;
                case 'd': // Days
                    fromDate.setDate(today.getDate() - amount);
                    break;
                case 'w': // Weeks
                    fromDate.setDate(today.getDate() - (amount * 7));
                    break;
                case 'm': // Months
                    fromDate.setMonth(today.getMonth() - amount);
                    break;
                case 'y': // Years
                    fromDate.setFullYear(today.getFullYear() - amount);
                    break;
            }

            const formatDate = (date) => {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            };
            
            dateFromInput.val(formatDate(fromDate));
            dateToInput.val(formatDate(today));
            
            // Auto Submit form
            $('#filterForm').submit();
        }

        // AJAX form submission for filtering
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            $('#complaintsTableContainer').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            const formData = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#complaintsTableContainer').html($(response).find('#complaintsTableContainer').html());
                    $('#complaintsModalsContainer').html($(response).find('#complaintsModalsContainer').html());
                    if ($.fn.DataTable.isDataTable('#complaintsTable')) {
                        $('#complaintsTable').DataTable().destroy();
                    }
                    $('#complaintsTable').DataTable({
                        responsive: false,
                        scrollX: true,
                        order: [],
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
                        ]
                    });
                },
                error: function() {
                    $('#complaintsTableContainer').html('<div class="alert alert-danger">Error loading complaints. Please try again.</div>');
                }
            });
        });

        // Custom date picker for dd/mm/yyyy format
        document.querySelectorAll('.date-picker').forEach(function(input) {
            // Create date picker popup
            const picker = document.createElement('div');
            picker.className = 'custom-date-picker';
            picker.style.cssText = `
                position: absolute;
                background: white;
                border: 1px solid #ccc;
                border-radius: 4px;
                padding: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                z-index: 1000;
                display: none;
                min-width: 250px;
            `;
            
            // Month/Year header
            const header = document.createElement('div');
            header.style.cssText = 'display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;';
            
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '&lt;';
            prevBtn.style.cssText = 'background: none; border: none; cursor: pointer; padding: 5px 10px;';
            
            const monthYear = document.createElement('span');
            monthYear.style.cssText = 'font-weight: bold;';
            
            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '&gt;';
            nextBtn.style.cssText = 'background: none; border: none; cursor: pointer; padding: 5px 10px;';
            
            header.appendChild(prevBtn);
            header.appendChild(monthYear);
            header.appendChild(nextBtn);
            
            // Days grid
            const daysGrid = document.createElement('div');
            daysGrid.style.cssText = 'display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px;';
            
            // Day names
            const dayNames = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
            dayNames.forEach(day => {
                const dayEl = document.createElement('div');
                dayEl.textContent = day;
                dayEl.style.cssText = 'text-align: center; font-weight: bold; font-size: 12px; padding: 5px;';
                daysGrid.appendChild(dayEl);
            });
            
            picker.appendChild(header);
            picker.appendChild(daysGrid);
            document.body.appendChild(picker);
            
            let currentDate = new Date();
            let selectedDate = null;
            
            function renderCalendar(date) {
                const year = date.getFullYear();
                const month = date.getMonth();
                
                monthYear.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                
                // Clear existing day cells (keep day names)
                while (daysGrid.children.length > 7) {
                    daysGrid.removeChild(daysGrid.lastChild);
                }
                
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                
                // Empty cells for days before first day
                for (let i = 0; i < firstDay; i++) {
                    const empty = document.createElement('div');
                    daysGrid.appendChild(empty);
                }
                
                // Day cells
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayCell = document.createElement('div');
                    dayCell.textContent = day;
                    dayCell.style.cssText = 'text-align: center; padding: 5px; cursor: pointer; border-radius: 3px;';
                    dayCell.addEventListener('mouseover', () => dayCell.style.background = '#e9ecef');
                    dayCell.addEventListener('mouseout', () => dayCell.style.background = '');
                    
                    dayCell.addEventListener('click', () => {
                        const selected = new Date(year, month, day);
                        const dd = String(selected.getDate()).padStart(2, '0');
                        const mm = String(selected.getMonth() + 1).padStart(2, '0');
                        const yyyy = selected.getFullYear();
                        input.value = `${dd}/${mm}/${yyyy}`;
                        picker.style.display = 'none';
                        input.setCustomValidity('');
                    });
                    
                    daysGrid.appendChild(dayCell);
                }
            }
            
            prevBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });
            
            nextBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
            
            input.addEventListener('focus', function() {
                const rect = input.getBoundingClientRect();
                picker.style.top = (rect.bottom + window.scrollY) + 'px';
                picker.style.left = rect.left + 'px';
                picker.style.display = 'block';
                
                // Parse current value if exists
                if (input.value) {
                    const parts = input.value.split('/');
                    if (parts.length === 3) {
                        currentDate = new Date(parts[2], parts[1] - 1, parts[0]);
                    }
                }
                renderCalendar(currentDate);
            });
            
            // Close picker when clicking outside
            document.addEventListener('click', function(e) {
                if (!picker.contains(e.target) && e.target !== input) {
                    picker.style.display = 'none';
                }
            });
            
            // Input mask for manual entry
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 8) value = value.slice(0, 8);
                
                if (value.length >= 2) {
                    value = value.slice(0, 2) + '/' + value.slice(2);
                }
                if (value.length >= 5) {
                    value = value.slice(0, 5) + '/' + value.slice(5);
                }
                
                e.target.value = value;
            });
            
            input.addEventListener('blur', function(e) {
                const value = e.target.value;
                const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
                if (value && !regex.test(value)) {
                    e.target.setCustomValidity('Please enter date in DD/MM/YYYY format');
                } else {
                    e.target.setCustomValidity('');
                }
            });
        });
    });
</script>
@endpush