@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<style>
    /* Live Dashboard Professional Styles */
    .live-dashboard {
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        min-height: 100vh;
    }

    .dashboard-header {
        text-align: center;
        margin-bottom: 20px;
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .dashboard-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 5px;
        letter-spacing: 2px;
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .dashboard-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        padding: 18px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.assigned::before {
        background: linear-gradient(90deg, #51cf66 0%, #40c057 100%);
    }

    .stat-card.unassigned::before {
        background: linear-gradient(90deg, #ff6b6b 0%, #fa5252 100%);
    }

    .stat-card.high-priority::before {
        background: linear-gradient(90deg, #ff922b 0%, #fd7e14 100%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .stat-card.total {
        border-left: 5px solid #667eea;
    }

    .stat-card.assigned {
        border-left: 5px solid #51cf66;
    }

    .stat-card.unassigned {
        border-left: 5px solid #ff6b6b;
    }

    .stat-card.high-priority {
        border-left: 5px solid #ff922b;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: #333;
        margin-bottom: 3px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        font-size: 1.5rem;
        margin-bottom: 5px;
        opacity: 0.7;
    }

    .filter-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }



    .filter-btn {
        padding: 12px 30px;
        border: none;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        color: #666;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .filter-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .complaints-grid {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .complaint-card {
        background: white;
        border-radius: 8px;
        padding: 10px 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        position: relative;
        overflow: visible;
        animation: fadeInUp 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .complaint-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .complaint-card.priority-high {
        border-left: 4px solid #ff6b6b;
    }

    .complaint-card.priority-medium {
        border-left: 4px solid #ffd43b;
    }

    .complaint-card.priority-low {
        border-left: 4px solid #51cf66;
    }

    .card-ref {
        min-width: 100px;
        font-size: 1.1rem;
        font-weight: 800;
        color: #667eea;
    }

    .card-user {
        min-width: 150px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
    }

    .card-badges {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .badge-status-assigned {
        background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        color: white;
    }

    .badge-status-unassigned {
        background: linear-gradient(135deg, #ff6b6b 0%, #fa5252 100%);
        color: white;
    }

    .badge-status-pending {
        background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%);
        color: #333;
    }

    .badge-priority-high {
        background: linear-gradient(135deg, #ff6b6b 0%, #fa5252 100%);
        color: white;
    }

    .badge-priority-medium {
        background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%);
        color: #333;
    }

    .badge-priority-low {
        background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        color: white;
    }

    .card-description {
        flex: 1;
        font-size: 0.9rem;
        color: #555;
        line-height: 1.4;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #667eea;
        max-height: 50px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .card-time {
        min-width: 80px;
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
        text-align: right;
    }

    .card-assigned {
        min-width: 120px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        text-align: right;
    }

    .not-assigned {
        color: #999;
        font-style: italic;
    }

    .new-indicator {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #ff6b6b;
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 700;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .complaints-grid-container {
        padding-right: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .empty-state-icon {
        font-size: 5rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-state-text {
        font-size: 1.5rem;
        color: #666;
        font-weight: 600;
    }

    .last-updated {
        text-align: center;
        margin-top: 20px;
        color: #999;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.5rem;
        }

        .dashboard-subtitle {
            font-size: 0.85rem;
        }

        .stats-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .stat-card {
            padding: 12px;
        }

        .stat-number {
            font-size: 1.8rem;
        }

        .stat-label {
            font-size: 0.7rem;
        }

        .stat-icon {
            font-size: 1.2rem;
        }

        .filter-bar {
            flex-direction: column;
            gap: 8px;
        }

        .filter-btn {
            width: 100%;
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        .complaint-card {
            flex-wrap: wrap;
            padding: 10px;
            gap: 10px;
        }

        .card-ref {
            min-width: 80px;
            font-size: 1rem;
        }

        .card-user {
            min-width: 120px;
            font-size: 0.85rem;
        }

        .card-badges {
            width: 100%;
            order: 3;
        }

        .card-description {
            width: 100%;
            order: 4;
            font-size: 0.85rem;
        }

        .card-description::after {
            width: 300px;
            left: -10px;
        }

        .card-time {
            min-width: 60px;
            font-size: 0.75rem;
        }

        .card-assigned {
            min-width: 100px;
            font-size: 0.85rem;
        }

        .view-toggle-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }

    /* View Toggle Button */
    .view-toggle-btn {
        padding: 12px 25px;
        border: none;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .view-toggle-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .view-toggle-btn.active {
        background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
    }

    /* Card View Layout */
    .complaints-grid.card-view {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
    }

    .complaints-grid.card-view .complaint-card {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px;
        gap: 8px;
    }

    .complaints-grid.card-view .card-ref {
        font-size: 1rem;
        margin-bottom: 0;
    }

    .complaints-grid.card-view .card-user {
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .complaints-grid.card-view .card-badges {
        margin-bottom: 0;
        gap: 5px;
    }

    .complaints-grid.card-view .badge {
        padding: 3px 8px;
        font-size: 0.65rem;
    }

    .complaints-grid.card-view .card-description {
        width: 100%;
        margin-bottom: 0;
        max-height: 40px;
        font-size: 0.8rem;
        padding: 6px 8px;
    }

    .complaints-grid.card-view .card-description::after {
        top: 0;
        left: 100%;
        margin-left: 5px;
        margin-top: 0;
        width: 300px;
        font-size: 0.85rem;
        padding: 10px;
    }

    .complaints-grid.card-view .card-time {
        align-self: flex-end;
        margin-bottom: 0;
        font-size: 0.75rem;
    }

    .complaints-grid.card-view .card-assigned {
        align-self: flex-end;
        font-size: 0.8rem;
    }


    .card-description{
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .card-description::after{
        content: attr(data-full-text);
        position: absolute;
        left: 0;
        top: 100%;
        width: 400px;
        max-height: 300px;
        overflow-y: auto;
        background: #fff;
        box-shadow: 0 8px 20px rgba(0,0,0,.15);
        border-radius: 8px;
        padding: 12px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        margin-top: 5px;
        white-space: normal;
        line-height: 1.4;
        font-size: 0.9rem;
        color: #555;
    }

    .card-description:hover::after{
        opacity: 1;
        visibility: visible;
    }


    .live-status{
        text-align:center;
        margin-bottom:15px;
        font-size:14px;
        font-weight:600;
        color:#666;
    }

    .live-dot{
        width:10px;
        height:10px;
        background:#51cf66;
        border-radius:50%;
        display:inline-block;
        animation:pulse 1s infinite;
    }
</style>

<div class="live-dashboard">
    <div class="dashboard-header">
        <h1 class="dashboard-title">📋 Live Complaints Dashboard</h1>

        <p class="dashboard-subtitle"><span class="live-dot"></span> Real-time monitoring of active complaints</p>
    </div>

    <div class="stats-container">
        <div class="stat-card total">
            <div class="stat-icon">📊</div>
            <div class="stat-number" id="totalComplaints">0</div>
            <div class="stat-label">Total Complaints</div>
        </div>
        <div class="stat-card assigned">
            <div class="stat-icon">✅</div>
            <div class="stat-number" id="assignedComplaints">0</div>
            <div class="stat-label">Assigned</div>
        </div>
        <div class="stat-card unassigned">
            <div class="stat-icon">⏳</div>
            <div class="stat-number" id="unassignedComplaints">0</div>
            <div class="stat-label">Unassigned</div>
        </div>
        <div class="stat-card high-priority">
            <div class="stat-icon">🔥</div>
            <div class="stat-number" id="highPriorityComplaints">0</div>
            <div class="stat-label">High Priority</div>
        </div>
    </div>

    <div class="filter-bar">
        <button class="filter-btn active" data-filter="all">All Complaints</button>
        <button class="filter-btn" data-filter="assigned">Assigned</button>
        <button class="filter-btn" data-filter="unassigned">Unassigned</button>
        <button class="filter-btn" data-filter="high">High Priority</button>
        <button class="view-toggle-btn" id="viewToggleBtn">
            <span id="viewIcon">📋</span>
            <span id="viewText">Card View</span>
        </button>
    </div>

    <div class="complaints-grid-container">
        <div class="complaints-grid" id="complaintsGrid"></div>
    </div>

    <div class="last-updated">
        Last updated: <span id="lastUpdated">--</span>
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
let currentFilter = 'all';
let allComplaints = [];
let currentView = localStorage.getItem('complaintView') || 'row';

function getStatusBadge(status) {
    const statusLower = status.toLowerCase();

    // Pehle unassigned check karo
    if (statusLower.includes('unassigned')) {
        return '<span class="badge badge-status-unassigned">✗ Unassigned</span>';
    } 
    else if (
        statusLower.includes('assigned') || 
        statusLower.includes('in_progress')
    ) {
        return '<span class="badge badge-status-assigned">✓ Assigned</span>';
    } 
    else {
        return '<span class="badge badge-status-pending">⏳ Pending</span>';
    }
}

function getPriorityBadge(priority) {
    const priorityLower = priority.toLowerCase();
    if (priorityLower === 'high') {
        return '<span class="badge badge-priority-high">🔥 High</span>';
    } else if (priorityLower === 'medium') {
        return '<span class="badge badge-priority-medium">⚡ Medium</span>';
    } else {
        return '<span class="badge badge-priority-low">✓ Low</span>';
    }
}

function getPriorityClass(priority) {
    const priorityLower = priority.toLowerCase();
    if (priorityLower === 'high') return 'priority-high';
    if (priorityLower === 'medium') return 'priority-medium';
    return 'priority-low';
}

function getInitials(name) {
    if (!name) return '?';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
}

function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    if (hours < 24) return `${hours}h ago`;
    return `${days}d ago`;
}

function renderComplaintCard(complaint, isNew = false) {
    const priorityClass = getPriorityClass(complaint.priority);
    const assignedName = complaint.assigned_to_name || 'Not Assigned';
    const timeAgo = getTimeAgo(complaint.created_at);
    const isNewIndicator = isNew ? '<div class="new-indicator">NEW</div>' : '';
    const description = complaint.description || 'No description provided';

    return `
        <div class="complaint-card ${priorityClass}" data-id="${complaint.id}">
            ${isNewIndicator}
            <div class="card-ref">#${complaint.reference_number}</div>
            <div class="card-user">${complaint.user_name}</div>
            <div class="card-badges">
                ${getStatusBadge(complaint.status)}
                ${getPriorityBadge(complaint.priority)}
            </div>
            <div class="card-description" data-full-text="${description.replace(/"/g, '&quot;')}">
                ${description}
            </div>
            <div class="card-time">🕐 ${timeAgo}</div>
            <div class="card-assigned ${!complaint.assigned_to_name ? 'not-assigned' : ''}">
                ${assignedName}
            </div>
        </div>
    `;
}

function updateStats(complaints) {
    const total = complaints.length;
    const assigned = complaints.filter(c => c.assigned_to_name).length;
    const unassigned = total - assigned;
    const highPriority = complaints.filter(c => c.priority.toLowerCase() === 'high').length;

    $('#totalComplaints').text(total);
    $('#assignedComplaints').text(assigned);
    $('#unassignedComplaints').text(unassigned);
    $('#highPriorityComplaints').text(highPriority);
}

function filterComplaints(complaints) {
    switch(currentFilter) {
        case 'assigned':
            return complaints.filter(c => c.assigned_to_name);
        case 'unassigned':
            return complaints.filter(c => !c.assigned_to_name);
        case 'high':
            return complaints.filter(c => c.priority.toLowerCase() === 'high');
        default:
            return complaints;
    }
}

function renderComplaints(complaints, newComplaintIds = []) {
    const filtered = filterComplaints(complaints);
    let html = '';

    if (filtered.length === 0) {
        html = `
            <div class="empty-state" style="grid-column: 1 / -1;">
                <div class="empty-state-icon">📭</div>
                <div class="empty-state-text">No complaints to display</div>
            </div>
        `;
    } else {
        filtered.forEach(c => {
            const isNew = newComplaintIds.includes(c.id);
            html += renderComplaintCard(c, isNew);
        });
    }

    $('#complaintsGrid').html(html);
    updateStats(complaints);
    $('#lastUpdated').text(new Date().toLocaleTimeString());

    // Apply current view
    applyView();

    // Auto-scroll to top if there are new complaints
    if (newComplaintIds.length > 0) {
        $('.complaints-grid-container').scrollTop(0);
    }
}

function applyView() {
    if (currentView === 'card') {
        $('#complaintsGrid').addClass('card-view');
        $('#viewIcon').text('📋');
        $('#viewText').text('Row View');
        $('#viewToggleBtn').addClass('active');
    } else {
        $('#complaintsGrid').removeClass('card-view');
        $('#viewIcon').text('🗃️');
        $('#viewText').text('Card View');
        $('#viewToggleBtn').removeClass('active');
    }
}

function toggleView() {
    currentView = currentView === 'row' ? 'card' : 'row';
    localStorage.setItem('complaintView', currentView);
    applyView();
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

function fetchComplaints(manualRefresh = false) {

    $.get(DATA_URL, function(data) {
        if (!data || !Array.isArray(data.complaints)) return;

        const complaints = data.complaints;
        allComplaints = complaints;

        const newIds = complaints.map(c => c.id);
        const newComplaintIds = [];

        if (!isFirstLoad) {
            newIds.forEach(id => {
                if (!lastComplaintIds.includes(id)) {
                    newComplaintIds.push(id);
                }
            });
        }

        if (newComplaintIds.length > 0) {
            playSound();
        }

        renderComplaints(complaints, newComplaintIds);
        lastComplaintIds = newIds;
        isFirstLoad = false;

    });
}

$(document).ready(function() {
    fetchComplaints();
    setInterval(fetchComplaints, POLL_INTERVAL);

    // Filter buttons
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        renderComplaints(allComplaints);
    });

    // View toggle button
    $('#viewToggleBtn').on('click', function() {
        toggleView();
    });

    // Apply initial view
    applyView();

});
</script>
@endpush 