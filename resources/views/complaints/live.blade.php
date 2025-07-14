@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
<style>
    body.theme-light { background: #f4f8fb; color: #222; }
    body.theme-dark { background: #181c24; color: #f4f8fb; }
    body.theme-glass { background: linear-gradient(135deg, #e0e7ef 0%, #f4f8fb 100%); }
    body.theme-colorful { background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%); }
    .theme-dark .dashboard-title { color: #90caf9 !important; }
    .theme-dark .complaint-list-item { background: #232a36 !important; color: #f4f8fb; border-color: #222 !important; }
    .theme-dark .complaint-list-item .cm-user,
    .theme-dark .complaint-list-item .cm-meta,
    .theme-dark .complaint-list-item .cm-time,
    .theme-dark .complaint-list-item .bi {
        color: #e0e7ef !important;
    }
    .theme-dark .complaint-list-item .cm-ref { color: #42a5f5 !important; }
    .theme-glass .complaint-list-item { background: rgba(255,255,255,0.35) !important; box-shadow: 0 8px 32px 0 rgba(31,38,135,0.18); backdrop-filter: blur(8px); border: 1.5px solid rgba(255,255,255,0.18); }
    .theme-colorful .complaint-list-item { background: linear-gradient(135deg, #fda085 0%, #f6d365 100%) !important; color: #222; }
    .theme-switcher { display: flex; gap: 0.5rem; margin-bottom: 1.2rem; justify-content: center; }
    .theme-switcher button { border: none; background: #e0e7ef; color: #0d6efd; font-weight: 600; padding: 0.4rem 1.1rem; border-radius: 6px; cursor: pointer; transition: background 0.18s, color 0.18s; }
    .theme-switcher button.active { background: #0d6efd; color: #fff; }
    .dashboard-title { font-size: 2rem; font-weight: bold; letter-spacing: 1.5px; color: #0d6efd; margin-bottom: 1rem; text-shadow: 0 2px 8px rgba(13,110,253,0.08); text-align: center; }
    .complaints-list { width: 100%; max-width: 900px; margin: 0 auto 1.2rem auto; }
    .complaint-list-item { background: #fff; border-radius: 7px; box-shadow: 0 1px 4px 0 rgba(31, 38, 135, 0.07); padding: 0.7rem 1rem; margin-bottom: 0.7rem; display: flex; align-items: center; gap: 1.2rem; border-left: 4px solid #0d6efd; transition: box-shadow 0.18s; }
    .complaint-list-item .cm-ref { min-width: 120px; font-size: 1.05rem; font-weight: 700; color: #0d6efd; }
    .complaint-list-item .cm-user { min-width: 120px; font-size: 0.98rem; color: #333; font-weight: 500; }
    .complaint-list-item .cm-badges { display: flex; align-items: center; gap: 0.18rem; margin-bottom: 0.08rem; }
    .complaint-list-item .cm-badge { display: inline-block; font-size: 0.78rem; font-weight: 600; padding: 0.07rem 0.45rem; border-radius: 9px; margin-right: 0.02rem; letter-spacing: 0.2px; line-height: 1.1; }
    .cm-status-assigned { background: #eaf1fb; color: #0d6efd; }
    .cm-status-unassigned { background: #f8d7da; color: #b02a37; }
    .cm-priority-high { background: #f8d7da; color: #b02a37; }
    .cm-priority-medium { background: #fff3cd; color: #856404; }
    .cm-priority-low { background: #d1e7dd; color: #0f5132; }
    .complaint-list-item .cm-meta { min-width: 120px; font-size: 0.85rem; color: #555; }
    .complaint-list-item .cm-time { font-size: 0.78rem; color: #888; margin-top: 0.05rem; font-style: italic; display: flex; align-items: center; gap: 0.13rem; }
    .layout-switcher { display: flex; gap: 0.5rem; margin-bottom: 1.2rem; justify-content: center; }
    .layout-switcher button { border: none; background: #e0e7ef; color: #0d6efd; font-weight: 600; padding: 0.4rem 1.1rem; border-radius: 6px; cursor: pointer; transition: background 0.18s, color 0.18s; }
    .layout-switcher button.active { background: #0d6efd; color: #fff; }
    .metro-empty-message { text-align: center; color: #888; font-size: 1.2rem; margin-top: 100px; }
    .metro-map-container { width: 100%; max-width: 1200px; margin: 0 auto 2rem auto; position: relative; height: 340px; overflow-x: auto; }
    .metro-svg { height: 340px; min-width: 1000px; display: block; }
    @media (max-width: 900px) {
        .complaints-list { width: 98vw; }
        .complaint-list-item { flex-direction: column; align-items: flex-start; gap: 0.3rem; }
    }
</style>
<div class="container-fluid">
    <div class="dashboard-title">Live Complaints Dashboard</div>
    <div class="theme-switcher">
        <button class="active" data-theme="light">Light</button>
        <button data-theme="dark">Dark</button>
        <button data-theme="glass">Glass</button>
        <button data-theme="colorful">Colorful</button>
    </div>
    <div class="layout-switcher">
        <button class="active" data-layout="list">List</button>
        <button data-layout="metro">Metro Map</button>
    </div>
    <div id="layout-list" class="layout-section">
        <div class="complaints-list" id="complaintsList"></div>
    </div>
    <div id="layout-metro" class="layout-section" style="display:none;">
        <div class="metro-map-container">
            <svg class="metro-svg" id="metroSVG"></svg>
        </div>
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
        html += `<div class=\"complaint-list-item\">
            <div class=\"cm-ref\">${c.reference_number}</div>
            <div class=\"cm-user\"><i class=\"bi bi-person\"></i> ${c.user_name}</div>
            <div class=\"cm-badges\">
                ${getStatusBadge(c.status)}
                ${getPriorityBadge(c.priority)}
            </div>
            <div class=\"cm-meta\"><i class=\"bi bi-person-badge\"></i> ${c.assigned_to_name || 'Not Assigned'}</div>
            <div class=\"cm-time\"><i class=\"bi bi-clock\"></i> ${c.created_at}</div>
        </div>`;
    });
    $('#complaintsList').html(html);
}
function renderMetroMap(complaints) {
    console.log('MetroMap complaints:', complaints);
    const svg = $('#metroSVG');
    svg.empty();
    // Remove any previous message
    $('.metro-empty-message').remove();
    try {
        // Set SVG width/height dynamically
        const minW = 1000;
        const pxPerComplaint = 200;
        const w = Math.max(minW, complaints.length * pxPerComplaint);
        const h = 340;
        svg.attr('width', w);
        svg.attr('height', h);
        const n = complaints.length;
        if (!complaints || n === 0) {
            svg.after('<div class="metro-empty-message">No complaints to display.</div>');
            return;
        }
        // Build SVG content as string
        let svgContent = '';
        // Draw a smooth path (sine wave)
        let path = '';
        let nodes = [];
        const margin = 60;
        const usableW = w - 2 * margin;
        const usableH = h - 2 * margin;
        for (let i = 0; i < n; i++) {
            const x = margin + (usableW) * (i / (n - 1 || 1));
            const y = margin + usableH / 2 + Math.sin(i / (n - 1 || 1) * Math.PI * 2) * (usableH / 2 - 40);
            nodes.push({ x, y });
        }
        // Path string
        path += `M${nodes[0].x},${nodes[0].y}`;
        for (let i = 1; i < nodes.length; i++) {
            path += ` L${nodes[i].x},${nodes[i].y}`;
        }
        svgContent += `<path d="${path}" stroke="#0d6efd" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="8 8" />`;
        // Draw nodes
        complaints.forEach((c, i) => {
            const node = nodes[i];
            svgContent += `<circle class="metro-node" cx="${node.x}" cy="${node.y}" r="18" fill="#fff" stroke="#0d6efd" stroke-width="3" />`;
            svgContent += `<text class="metro-label" x="${node.x}" y="${node.y - 28}">${c.reference_number}</text>`;
            svgContent += `<text class="metro-label" x="${node.x}" y="${node.y + 32}" font-size="0.8rem" fill="#888">${c.user_name}</text>`;
        });
        svg.html(svgContent);
    } catch (err) {
        svg.after('<div class="metro-empty-message">Error rendering Metro Map. Check console for details.</div>');
        console.error('Metro Map Render Error:', err);
    }
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
        renderMetroMap(window.lastComplaints);
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