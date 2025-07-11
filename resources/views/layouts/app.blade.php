<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>

<body>
    @include('layouts.navbar')


    <main class="container py-4">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

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
    @stack('scripts')
</body>

</html>