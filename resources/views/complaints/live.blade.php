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

    /* Section Headings Styles */
    .section-heading {
        font-size: 1.4rem;
        font-weight: 700;
        color: #4a5568;
        margin: 25px 0 12px 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-heading.older {
        color: #e53e3e;
        margin-top: 40px;
    }

    .section-divider {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, rgba(0,0,0,0), rgba(0,0,0,0.1), rgba(0,0,0,0));
        margin: 30px 0;
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
        cursor: pointer;
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
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
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
        padding: 40px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        width: 100%;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 10px;
    }

    .empty-state-text {
        font-size: 1.2rem;
        color: #666;
        font-weight: 600;
    }

    .last-updated {
        text-align: center;
        margin-top: 20px;
        color: #999;
        font-size: 0.9rem;
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
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
    }

    .complaints-grid.card-view .complaint-card {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px;
        gap: 10px;
    }

    .complaints-grid.card-view .card-ref {
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    .complaints-grid.card-view .card-user {
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .complaints-grid.card-view .card-badges {
        margin-bottom: 0;
        gap: 5px;
    }

    .complaints-grid.card-view .badge {
        padding: 4px 10px;
        font-size: 0.7rem;
    }
    
    .complaints-grid.card-view .card-description {
        width: 100%;
        margin-bottom: 0;
        max-height: 50px;
        font-size: 0.85rem;
        padding: 8px 12px;
    }

    .complaints-grid.card-view .card-time { align-self: flex-end; margin-bottom: 0; font-size: 0.8rem; }
    .complaints-grid.card-view .card-assigned { align-self: flex-end; font-size: 0.85rem; }

    .live-dot {
        width: 10px;
        height: 10px;
        background: #51cf66;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 1s infinite;
    }

  /* ==========================================================================
   FINAL CLEAN & EXCELLENT FIXED OVERLAY MODAL STYLES (INNER SCROLL)
   ========================================================================== */
    .complaint-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(8px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 99999 !important;
        overflow: hidden;
        padding: 20px;
    }

    .complaint-modal-overlay.active {
        display: flex !important;
    }

    .complaint-modal {
        background: #ffffff;
        border-radius: 24px;
        max-width: 720px;
        width: 100%;
        max-height: 90vh;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
        position: relative;
        border: 1px solid rgba(226, 232, 240, 0.8);
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 20px 25px;
        border-radius: 24px 24px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .modal-title {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0;
        color: #ffffff !important;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white !important;
        font-size: 1.8rem;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        line-height: 1;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 25px;
        background: #f8fafc;
        border-radius: 0 0 24px 24px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .modal-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 20px;
        flex-shrink: 0;
    }

    .modal-meta-card {
        background: #ffffff;
        padding: 12px 16px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .meta-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 0.92rem;
        font-weight: 600;
        color: #1e293b;
    }

    .modal-description {
        background: #ffffff;
        padding: 22px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #4f46e5;
        box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        width: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        max-height: 250px;
    }

    .modal-description strong {
        color: #1e293b;
        font-size: 0.85rem;
        text-transform: uppercase;
        display: block;
        margin-bottom: 10px;
        flex-shrink: 0;
    }

    .modal-description p {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
        white-space: pre-wrap;
        word-break: break-word;
        overflow-y: auto; 
    }

    @media (max-width: 768px) {
    .modal-info-grid {
        grid-template-columns: 1fr;
    }
    .modal-description {
        max-height: 180px;
    }
    }
</style>

<div class="live-dashboard">
    <div class="dashboard-header">
        <h1 class="dashboard-title">📋 Live Unresolved Complaints Dashboard</h1>
        <p class="dashboard-subtitle"><span class="live-dot"></span> Real-time monitoring of active unresolved complaints</p>
    </div>

    <div class="stats-container">
        <div class="stat-card total" data-filter="all" style="cursor: pointer;">
            <div class="stat-icon">📊</div>
            <div class="stat-number" id="totalComplaints">0</div>
            <div class="stat-label">Total Complaints</div>
        </div>
        <div class="stat-card assigned" data-filter="assigned" style="cursor: pointer;">
            <div class="stat-icon">✅</div>
            <div class="stat-number" id="assignedComplaints">0</div>
            <div class="stat-label">Assigned</div>
        </div>
        <div class="stat-card unassigned" data-filter="unassigned" style="cursor: pointer;">
            <div class="stat-icon">⏳</div>
            <div class="stat-number" id="unassignedComplaints">0</div>
            <div class="stat-label">Unassigned</div>
        </div>
        <div class="stat-card high-priority" data-filter="high" style="cursor: pointer;">
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
        <h2 class="section-heading">📅 Today's Complaints</h2>
        <div class="complaints-grid" id="todayComplaintsGrid"></div>
        <hr class="section-divider">

        <!-- Older Pending Section -->
        <h2 class="section-heading older">⚠️ Older Pending Complaints</h2>
        <div class="complaints-grid" id="olderComplaintsGrid"></div>
    </div>

    <div class="last-updated">
        Last updated: <span id="lastUpdated">--</span>
    </div>
</div>

<!-- Complaint Modal -->
<div class="complaint-modal-overlay" id="complaintModal">
    <div class="complaint-modal">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Complaint Details</h3>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Dynamic content will be inserted here -->
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
let currentFilter = 'all';
let todayComplaints = [];
let olderComplaints = [];
let currentView = localStorage.getItem('complaintView') || 'card';
let complaintsData = {};

function getStatusBadge(status) {
    const statusLower = status.toLowerCase();
    if (statusLower.includes('unassigned')) {
        return '<span class="badge badge-status-unassigned">✗ Unassigned</span>';
    } else if (statusLower.includes('assigned') || statusLower.includes('in_progress')) {
        return '<span class="badge badge-status-assigned">✓ Assigned</span>';
    } else {
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
        <div class="complaint-card ${priorityClass}" data-id="${complaint.id}" onclick="showComplaintModal(${complaint.id})">
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

function updateStats(todayList, olderList) {
    const combined = [...todayList, ...olderList];
    const total = combined.length;
    const assigned = combined.filter(c => c.assigned_to_name).length;
    const unassigned = total - assigned;
    const highPriority = combined.filter(c => c.priority.toLowerCase() === 'high').length;

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

function renderComplaints(todayList, olderList, newComplaintIds = []) {
    const filteredToday = filterComplaints(todayList);
    const filteredOlder = filterComplaints(olderList);
    
    let todayHtml = '';
    let olderHtml = '';

    if (filteredToday.length === 0) {
        todayHtml = `
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <div class="empty-state-text">No complaints filed today</div>
            </div>
        `;
    } else {
        filteredToday.forEach(c => {
            const isNew = newComplaintIds.includes(c.id);
            todayHtml += renderComplaintCard(c, isNew);
        });
    }

    // Render Older Pending Section
    if (filteredOlder.length === 0) {
        olderHtml = `
            <div class="empty-state">
                <div class="empty-state-icon">🎉</div>
                <div class="empty-state-text">No pending complaints from previous days</div>
            </div>
        `;
    } else {
        filteredOlder.forEach(c => {
            const isNew = newComplaintIds.includes(c.id);
            olderHtml += renderComplaintCard(c, isNew);
        });
    }

    $('#todayComplaintsGrid').html(todayHtml);
    $('#olderComplaintsGrid').html(olderHtml);

    updateStats(todayList, olderList);
    $('#lastUpdated').text(new Date().toLocaleTimeString());

    applyView();

    if (newComplaintIds.length > 0) {
        $('.complaints-grid-container').scrollTop(0);
    }
}

function applyView() {
    if (currentView === 'card') {
        $('#todayComplaintsGrid, #olderComplaintsGrid').addClass('card-view');
        $('#viewIcon').text('📋');
        $('#viewText').text('Row View');
        $('#viewToggleBtn').addClass('active');
    } else {
        $('#todayComplaintsGrid, #olderComplaintsGrid').removeClass('card-view');
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

function showComplaintModal(complaintId) {
    const complaint = complaintsData[complaintId];
    if (!complaint) return;

    const assignedName = complaint.assigned_to_name || 'Not Assigned';
    const timeAgo = getTimeAgo(complaint.created_at);
    const description = complaint.description || 'No description provided';
    
    const roomNumber = complaint.room_number || 'N/A';
    const intercom = complaint.intercom || 'N/A';
    const networkType = complaint.network_type || 'N/A';
    const requestType = complaint.request_type || 'N/A';
    const section = complaint.section || 'N/A';

    const modalBody = `
        <div class="modal-info-grid">
            <div class="modal-meta-card">
                <span class="meta-label">👤 User / Client</span>
                <span class="meta-value">${complaint.user_name}</span>
            </div>
            <div class="modal-meta-card" style="display: flex; flex-direction: column; gap: 8px; background: #fff; padding: 12px 16px; border-radius: 14px; border: 1px solid #e2e8f0;">
                <span class="meta-label">📊 Status & Priority</span>
                <div class="modal-badges" style="margin: 0; gap: 6px; display: flex; align-items: center;">
                    ${getStatusBadge(complaint.status)}
                    ${getPriorityBadge(complaint.priority)}
                </div>
            </div>

            <div class="modal-meta-card">
                <span class="meta-label">📍 Location (Room)</span>
                <span class="meta-value">Room No: ${roomNumber}</span>
            </div>

            <div class="modal-meta-card">
                <span class="meta-label">📞 Intercom</span>
                <span class="meta-value">${intercom}</span>
            </div>

            <div class="modal-meta-card">
                <span class="meta-label">🏢 Section</span>
                <span class="meta-value">${section}</span>
            </div>

            <div class="modal-meta-card">
                <span class="meta-label">🔌 Request & Network</span>
                <span class="meta-value">${requestType} (${networkType})</span>
            </div>

            <div class="modal-meta-card">
                <span class="meta-label">🛠️ Assigned Officer</span>
                <span class="meta-value" style="color: ${complaint.assigned_to_name ? '#1e293b' : '#94a3b8'}">
                    ${complaint.assigned_to_name ? '👤 ' + assignedName : '⏳ Not Assigned Yet'}
                </span>
            </div>

            <div class="modal-meta-card">
                <span class="meta-label">🕐 Logged Time</span>
                <span class="meta-value">${timeAgo} <small style="color:#64748b; font-weight:400;">(${complaint.created_at})</small></span>
            </div>

        </div>

        <!-- Description Box -->
        <div class="modal-description">
            <strong>📝 Issue Description</strong><p style="white-space: pre-wrap;">${description}</p>
        </div>
    `;

    $('#modalTitle').text(`Ticket #${complaint.reference_number}`);
    $('#modalBody').html(modalBody);
    $('#complaintModal').addClass('active');
}

function closeModal() {
    $('#complaintModal').removeClass('active');
}

// Close modal when clicking outside
$(document).on('click', '.complaint-modal-overlay', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal on Escape key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

function fetchComplaints() {
    $.get(DATA_URL, function(data) {
        if (!data) return;

        todayComplaints = data.today || [];
        olderComplaints = data.older_pending || [];

        // Store complaints data for modal
        const combined = [...todayComplaints, ...olderComplaints];
        combined.forEach(c => {
            complaintsData[c.id] = c;
        });

        const newIds = combined.map(c => c.id);
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

        renderComplaints(todayComplaints, olderComplaints, newComplaintIds);
        lastComplaintIds = newIds;
        isFirstLoad = false;

    });
}

$(document).ready(function() {
    fetchComplaints();
    setInterval(fetchComplaints, POLL_INTERVAL);

    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        renderComplaints(todayComplaints, olderComplaints);
    });

    $('.stat-card').on('click', function() {
        const targetFilter = $(this).data('filter');
        currentFilter = targetFilter;
        
        $('.filter-btn').removeClass('active');
        $(`.filter-btn[data-filter="${targetFilter}"]`).addClass('active');
        
        renderComplaints(todayComplaints, olderComplaints);
    });

    $('#viewToggleBtn').on('click', function() {
        toggleView();
    });

    // Apply initial view
    applyView();
});
</script>
@endpush