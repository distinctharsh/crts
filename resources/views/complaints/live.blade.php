@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
<style>
    .live-dashboard-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 1.2rem 0.3rem 0.5rem 0.3rem;
    }
    .dashboard-title {
        font-size: 2rem;
        font-weight: bold;
        letter-spacing: 1.5px;
        color: #0d6efd;
        margin-bottom: 1rem;
        text-shadow: 0 2px 8px rgba(13,110,253,0.08);
    }
    .complaints-masonry {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.5rem;
        width: 100%;
        max-width: 1800px;
        margin-bottom: 1.2rem;
    }
    .complaint-masonry-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 1px 6px 0 rgba(31, 38, 135, 0.08);
        padding: 0.6rem 0.7rem 0.5rem 0.7rem;
        display: flex;
        flex-direction: column;
        min-height: 80px;
        position: relative;
        transition: box-shadow 0.18s, transform 0.18s;
        border: 1.5px solid #f0f4fa;
        cursor: pointer;
        overflow: hidden;
        animation: fadeInUp 0.6s;
    }
    .complaint-masonry-card:hover {
        box-shadow: 0 4px 16px 0 rgba(13,110,253,0.10);
        transform: translateY(-2px) scale(1.025);
        border-color: #0d6efd18;
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(24px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .cm-ref {
        font-size: 0.98rem;
        font-weight: 600;
        color: #0d6efd;
        margin-bottom: 0.08rem;
        letter-spacing: 0.5px;
        word-break: break-all;
    }
    .cm-user {
        font-size: 0.93rem;
        color: #333;
        font-weight: 500;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.18rem;
        margin-bottom: 0.08rem;
    }
    .cm-badges {
        display: flex;
        align-items: center;
        gap: 0.18rem;
        margin-bottom: 0.08rem;
    }
    .cm-badge {
        display: inline-block;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.07rem 0.45rem;
        border-radius: 9px;
        margin-right: 0.02rem;
        letter-spacing: 0.2px;
        line-height: 1.1;
    }
    .cm-status-assigned { background: #eaf1fb; color: #0d6efd; }
    .cm-status-unassigned { background: #f8d7da; color: #b02a37; }
    .cm-priority-high { background: #f8d7da; color: #b02a37; }
    .cm-priority-medium { background: #fff3cd; color: #856404; }
    .cm-priority-low { background: #d1e7dd; color: #0f5132; }
    .cm-meta {
        font-size: 0.82rem;
        color: #555;
        margin-bottom: 0.03rem;
        display: flex;
        align-items: center;
        gap: 0.18rem;
        flex-wrap: wrap;
    }
    .cm-meta i {
        font-size: 0.98em;
        margin-right: 0.13em;
        opacity: 0.7;
    }
    .cm-time {
        font-size: 0.78rem;
        color: #888;
        margin-top: 0.05rem;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 0.13rem;
    }
    @media (max-width: 900px) {
        .complaints-masonry { gap: 0.3rem; }
        .complaint-masonry-card { min-width: 92vw; max-width: 98vw; }
    }
</style>
<div class="live-dashboard-container">
    <div class="dashboard-title">Live Complaints Dashboard</div>
    <div class="complaints-masonry" id="complaintsMasonry">
        <!-- Complaint cards will be rendered here by JS -->
    </div>
    <div style="width:100%;max-width:1800px;margin-top:1.2rem;">
        <table id="complaintsTable" class="display table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Reference No</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Assigned To</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be added by JS -->
            </tbody>
        </table>
    </div>
</div>
<!-- Notification Sound -->
<audio id="notifySound" src="{{ asset('sounds/notify.mp3') }}" preload="auto"></audio>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<script>
const DATA_URL = "{{ route('complaints.liveData') }}";
const POLL_INTERVAL = 5000;
let lastComplaintIds = [];
let lastAssignedMap = {};
let dtTable;

function getStatusBadge(status) {
    if (status.toLowerCase() === 'assigned')
        return '<span class="cm-badge cm-status-assigned"><i class="bi bi-person-check"></i>Assigned</span>';
    else
        return '<span class="cm-badge cm-status-unassigned"><i class="bi bi-person-x"></i>Unassigned</span>';
}
function getPriorityBadge(priority) {
    if (priority.toLowerCase() === 'high')
        return '<span class="cm-badge cm-priority-high"><i class="bi bi-exclamation-triangle"></i>High</span>';
    if (priority.toLowerCase() === 'medium')
        return '<span class="cm-badge cm-priority-medium"><i class="bi bi-exclamation-circle"></i>Medium</span>';
    return '<span class="cm-badge cm-priority-low"><i class="bi bi-arrow-down-circle"></i>Low</span>';
}

function renderComplaintsMasonry(complaints) {
    const grid = $('#complaintsMasonry');
    let html = '';
    complaints.forEach(c => {
        let animateClass = '';
        if (!lastComplaintIds.includes(c.id)) {
            animateClass = 'animate__animated animate__fadeInUp';
        }
        html += `<div class="complaint-masonry-card ${animateClass}" data-id="${c.id}">
            <div class="cm-ref">${c.reference_number}</div>
            <div class="cm-user"><i class="bi bi-person"></i> ${c.user_name}</div>
            <div class="cm-badges">
                ${getStatusBadge(c.status)}
                ${getPriorityBadge(c.priority)}
            </div>
            <div class="cm-meta"><i class="bi bi-person-badge"></i> ${c.assigned_to_name || 'Not Assigned'}</div>
            <div class="cm-time"><i class="bi bi-clock"></i> ${c.created_at}</div>
        </div>`;
    });
    grid.html(html);
    // Remove animation class after animation
    setTimeout(() => {
        $('.complaint-masonry-card.animate__animated').removeClass('animate__animated animate__fadeInUp');
    }, 1200);
}

function playSound() {
    const audio = document.getElementById('notifySound');
    if (audio) {
        audio.currentTime = 0;
        const playPromise = audio.play();
        if (playPromise !== undefined) {
            playPromise.catch(error => {
                console.log("Autoplay blocked, waiting for user interaction");
            });
        }
    }
}

function renderTable(complaints) {
    if (!dtTable) {
        dtTable = $('#complaintsTable').DataTable({
            paging: true,
            searching: true,
            info: true,
            order: [[5, 'desc']],
            columnDefs: [
                { targets: [5], type: 'date' }
            ]
        });
    }
    // Track existing IDs
    let currentIds = dtTable.rows().data().toArray().map(row => row[0]);
    let newRows = [];
    complaints.forEach(c => {
        let rowId = c.reference_number;
        let assignedTo = c.assigned_to_name || 'Not Assigned';
        let rowData = [
            c.reference_number,
            c.user_name,
            c.status,
            c.priority,
            assignedTo,
            c.created_at
        ];
        // If not present, add with animation
        if (!currentIds.includes(c.reference_number)) {
            let rowNode = dtTable.row.add(rowData).draw(false).node();
            $(rowNode).addClass('animate__animated animate__fadeInDown');
            newRows.push(rowNode);
        }
    });
    // Remove rows not in new data
    dtTable.rows().every(function() {
        let data = this.data();
        if (!complaints.find(c => c.reference_number === data[0])) {
            this.remove();
        }
    });
    dtTable.draw(false);
    // Play sound if new row
    if (newRows.length > 0) playSound();
    // Remove animation class after animation
    setTimeout(() => {
        newRows.forEach(row => $(row).removeClass('animate__animated animate__fadeInDown'));
    }, 1200);
}

function pollComplaints() {
    $.get(DATA_URL, function(data) {
        let newIds = data.map(c => c.id);
        let assignedMap = {};
        data.forEach(c => assignedMap[c.id] = c.assigned_to);
        // Masonry grid
        renderComplaintsMasonry(data);
        // DataTable
        renderTable(data);
        lastComplaintIds = newIds;
        lastAssignedMap = assignedMap;
    });
}

$(document).ready(function() {
    pollComplaints();
    setInterval(pollComplaints, POLL_INTERVAL);
});
</script>

@endpush 