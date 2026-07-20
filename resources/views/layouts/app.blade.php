<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @auth
    <meta name="current-user-id" content="{{ auth()->id() }}">
    @endauth
    <title>Complaint Redressal Ticketing (CRT) System</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- CSS -->
    <link href="{{ asset('css/tom-select.bootstrap5.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">

    <!-- JS -->
    <script src="{{ asset('js/tom-select.complete.min.js') }}"></script>

    <!-- DataTables CSS (global) -->
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.bootstrap5.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/buttons.bootstrap5.min.css') }}">

    <style>
        body {
            padding-top: 100px;
        }


        .breadcrumb {
            /* margin-left: 85%; */
            display: flex;
            justify-content: end;
        }


        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        /* Main vertical line */
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
            /* Solid line for the main timeline */
            margin-left: -1px;
        }

        /* Dotted connecting lines between circles */
        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 24px;
            /* Adjust based on your circle size */
            bottom: -1.5rem;
            width: 2px;
            background: linear-gradient(to bottom, #adb5bd 10%, transparent 0%);
            background-size: 2px 8px;
            /* Adjust dotted line pattern */
            background-repeat: repeat-y;
        }

        /* Circle styling (keep your existing classes) */
        .position-absolute.start-0.translate-middle {
            left: -1.5rem !important;
            z-index: 2;
            border: 2px solid white;
            /* Creates nice border effect */
            box-shadow: 0 0 0 2px #e9ecef;
            /* Matches timeline color */
        }



        div.dataTables_wrapper div.dataTables_paginate {
            text-align: right !important;
            float: right !important;
        }

        #usersTable_filter {
            float: right;
        }

        .filter-card {
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(13, 110, 253, 0.08);
            margin-bottom: 1rem !important;
            padding-bottom: 0 !important;
        }

        .filter-card .card-header {
            border-radius: 16px 16px 0 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Reduce card body padding */
        .card-body,
        .filter-card .card-body {
            padding: 1rem 1.25rem !important;
        }

        /* Reduce table cell padding */
        .table> :not(caption)>*>* {
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }

        /* Reduce DataTable header/footer margin */
        .dataTables_wrapper .datatable-header,
        .dataTables_wrapper .datatable-footer {
            margin-bottom: 0.5rem !important;
            margin-top: 0.5rem !important;
        }

        .swal2-container.custom-swal-toast-container {
            padding-top: 110px !important; 
            z-index: 1050 !important;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')


    <main class="container py-4">
        @yield('content')
    </main>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    @if(auth()->check() && (auth()->user()->isManager() || auth()->user()->isVM() || auth()->user()->isNFO()))
    <div id="complaintNotification"
        style="
        position:fixed;
        bottom:20px;
        right:-400px;
        width:320px;
        background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding:0;
        border-radius:12px;
        box-shadow:0 8px 24px rgba(0,0,0,0.15);
        transition:right 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index:9999;
        overflow:hidden;
        ">

        <div style="
            background:rgba(255,255,255,0.15);
            padding:16px 20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            border-bottom:1px solid rgba(255,255,255,0.2);
        ">
            <div style="display:flex;align-items:center;gap:10px;">
                <i class="bi bi-bell-fill" style="color:#fff;font-size:18px;"></i>
                <strong style="font-size:15px;color:#fff;font-weight:600;">Complaint Summary</strong>
            </div>

            <button onclick="hideNotification()"
            style="
                border:none;
                background:rgba(255,255,255,0.2);
                color:#fff;
                font-size:20px;
                cursor:pointer;
                line-height:1;
                padding:6px 10px;
                border-radius:6px;
                transition:background 0.2s;
            "
            onmouseover="this.style.background='rgba(255,255,255,0.3)'"
            onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            ×
            </button>
        </div>

        <div id="notificationContent" style="padding:20px;background:#fff;"></div>

        </div>

    <script>
        window.canShowNotifications = true;
    </script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    @endif

    @stack('scripts')
    
    <script>
    function showToast(message, type = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                container: 'custom-swal-toast-container'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }

    @if(session('success'))
    showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
    showToast('{{ session('error') }}', 'error');
    @endif

    $(document).on('submit', '.close-ticket-form, .assign-ticket-form, .revert-ticket-form', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var form = $(this);
        var complaintId = form.data('complaint-id');
        var formData = new FormData(this);
        var formClass = form.attr('class');
        var modalId, loadingText, buttonText, successMessage;
        
        if (formClass.includes('close-ticket-form')) {
            modalId = 'closeModal';
            loadingText = 'Closing...';
            buttonText = 'Close Ticket';
            successMessage = 'Ticket closed successfully!';
            formData.append('_method', 'PUT');
        } else if (formClass.includes('assign-ticket-form')) {
            modalId = 'assignModal';
            loadingText = 'Assigning...';
            buttonText = 'Assign';
            successMessage = 'Ticket assigned successfully!';
        } else if (formClass.includes('revert-ticket-form')) {
            modalId = 'revertModal';
            loadingText = 'Reverting...';
            buttonText = 'Revert';
            successMessage = 'Ticket reverted successfully!';
        }
        
        var modal = $('#' + modalId + complaintId);
        var submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + loadingText);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                modal.modal('hide');
                showToast(successMessage, 'success');
                if (formClass.includes('close-ticket-form')) {
                    var statusBadge = $('tr[data-complaint-id="' + complaintId + '"]').find('.status-badge');
                    if (statusBadge.length) {
                        statusBadge.removeClass('bg-warning bg-info bg-primary bg-secondary')
                            .addClass('bg-secondary')
                            .text('Closed');
                    }
                    
                    var actionCell = $('tr[data-complaint-id="' + complaintId + '"]').find('.action-buttons');
                    if (actionCell.length) {
                        actionCell.find('.btn-close-ticket').remove();
                    }
                }
                
                if (formClass.includes('assign-ticket-form') || formClass.includes('revert-ticket-form')) {
                    if (formClass.includes('revert-show-form')) {
                        location.reload();
                    } else {
                        var row = $('tr[data-complaint-id="' + complaintId + '"]');
                        if (row.length) {
                            row.attr('data-status', response.status_name);
                            row.attr('data-assigned-to', response.assigned_to_id);
                            row.attr('data-is-unassigned', response.is_unassigned ? 'true' : 'false');
                            row.attr('data-is-completed', response.is_completed ? 'true' : 'false');
                            row.attr('data-is-closed', response.is_closed ? 'true' : 'false');
                            
                            if (response.assigned_to) {
                                var assignedToCell = row.find('.assigned-to-cell');
                                if (assignedToCell.length) {
                                    assignedToCell.text(response.assigned_to);
                                }
                            }
                            
                            if (response.status && response.status_color) {
                                var statusBadge = row.find('.status-badge');
                                if (statusBadge.length) {
                                    statusBadge.removeClass('bg-warning bg-info bg-primary bg-secondary')
                                        .addClass('bg-' + response.status_color)
                                        .text(response.status);
                                }
                            }
                            
                            var actionCell = row.find('.action-buttons');
                            var userRole = row.attr('data-user-role');
                            var currentUserId = $('meta[name="current-user-id"]').attr('content');
                            
                            actionCell.find('.btn-assign-reassign, .btn-revert, .btn-close-ticket').remove();
                            
                            if (userRole === 'manager') {
                                if (response.status_name === 'completed') {
                                    actionCell.append('<button type="button" class="btn btn-sm btn-success ms-1 btn-close-ticket" data-bs-toggle="modal" data-bs-target="#closeModal' + complaintId + '">Close</button>');
                                }
                                if (response.status_name !== 'completed' && response.status_name !== 'closed') {
                                    var buttonText = response.assigned_to_id ? 'Reassign' : 'Assign';
                                    actionCell.append('<button type="button" class="btn btn-sm btn-primary btn-assign-reassign" data-bs-toggle="modal" data-bs-target="#assignModal' + complaintId + '">' + buttonText + '</button>');
                                }
                            }
                            else if (userRole === 'vm') {
                                var canAssign = (response.is_unassigned || response.assigned_to_id == currentUserId) && response.status_name !== 'completed' && response.status_name !== 'closed';
                                if (canAssign) {
                                    actionCell.append('<button type="button" class="btn btn-sm btn-primary btn-assign-reassign" data-bs-toggle="modal" data-bs-target="#assignModal' + complaintId + '">Assign</button>');
                                }
                                var canRevert = response.assigned_to_id == currentUserId && response.status_name !== 'completed' && response.status_name !== 'closed';
                                if (canRevert) {
                                    actionCell.append('<button type="button" class="btn btn-sm btn-warning btn-revert" data-bs-toggle="modal" data-bs-target="#revertModal' + complaintId + '">Revert</button>');
                                }
                            }
                            else if (userRole === 'nfo') {
                                var canReassign = response.assigned_to_id == currentUserId && !response.is_completed && !response.is_closed;
                                if (canReassign) {
                                    actionCell.append('<button type="button" class="btn btn-sm btn-primary btn-assign-reassign" data-bs-toggle="modal" data-bs-target="#assignModal' + complaintId + '">Reassign</button>');
                                }
                            }
                        }
                    }
                }
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON?.message || 'Error processing request. Please try again.';
                showToast(errorMessage, 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(buttonText);
                form[0].reset();
            }
        });
        
        return false;
    });
    </script>
</body>

</html>