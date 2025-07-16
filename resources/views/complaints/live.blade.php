@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="container-fluid">
    <div class="dashboard-title">Live Complaints Dashboard</div>
    <div class="theme-switcher">
        <button class="active" data-theme="light">Light</button>
        <button data-theme="colorful">Colorful</button>
    </div>
    <div class="layout-switcher" style="display:none;">
        <button class="active" data-layout="list">List</button>
    </div>
    <div id="layout-list" class="layout-section">
        <div class="complaints-list" id="complaintsList"></div>
    </div>
</div>
<audio id="notifySound" src="{{ asset('sounds/notify.mp3') }}" preload="auto"></audio>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script>
const DATA_URL = "{{ route('complaints.liveData') }}";
const POLL_INTERVAL = 5000;
let lastComplaintIds = [];
let isFirstLoad = true;

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
function renderComplaintsList(complaints) {
    let html = '';
    complaints.forEach(c => {
        // Get last 5 digits of reference number
        const refLast5 = c.reference_number ? c.reference_number.slice(-5) : '';
        html += `<div class=\"complaint-list-item\" style=\"display: flex; flex-wrap: wrap; align-items: center; gap: 18px; padding: 10px 0; border-bottom: 1px solid #eee;\">`
            + `<div class=\"cm-ref\" style=\"font-weight: bold; font-size: 1.2em; min-width: 70px;\">${refLast5}</div>`
            + `<div class=\"cm-user\"><i class=\"bi bi-person\"></i> ${c.user_name}</div>`
            + `<div class=\"cm-badges\">${getPriorityBadge(c.priority)}</div>`
            + `<div class=\"cm-desc\" style=\"max-width: 350px; white-space: pre-wrap; word-break: break-word; color: #222;\">${c.description ? c.description : ''}</div>`
            + `<div class=\"cm-time\"><i class=\"bi bi-clock\"></i> ${c.created_at}</div>`
            + `</div>`;
    });
    $('#complaintsList').html(html);
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
function fetchComplaints() {
    $.get(DATA_URL, function(data) {
        if (!data || !Array.isArray(data.complaints)) return;
        const complaints = data.complaints;
        window.lastComplaints = complaints;
        renderComplaintsList(complaints);
        let newIds = complaints.map(c => c.id);
        if (!isFirstLoad && newIds.length > lastComplaintIds.length) playSound();
        lastComplaintIds = newIds;
        isFirstLoad = false;
    });
}
$(document).ready(function() {
    fetchComplaints();
    setInterval(fetchComplaints, POLL_INTERVAL);
    // Layout switcher
    $('.layout-switcher button').on('click', function() {
        $('.layout-switcher button').removeClass('active');
        $(this).addClass('active');
        const layout = $(this).data('layout');
        $('.layout-section').hide();
        $(`#layout-${layout}`).show();
        if (layout === 'metro') {
            // Redraw metro map on show (for correct sizing)
            setTimeout(() => { renderMetroMap(window.lastComplaints); }, 100);
        }
    });
    // Theme switcher
    $('.theme-switcher button').on('click', function() {
        $('.theme-switcher button').removeClass('active');
        $(this).addClass('active');
        const theme = $(this).data('theme');
        $('body').removeClass('theme-light theme-dark theme-glass theme-colorful').addClass('theme-' + theme);
    });
    // Default theme
    $('body').addClass('theme-light');
});
</script>
@endpush 