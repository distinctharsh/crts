@extends('layouts.app')

@section('content')
<style>
    body, html {
        background: #f4f8fb;
        height: 100%;
        margin: 0;
        padding: 0;
    }
    .live-dashboard-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 2rem 1rem;
    }
    .dashboard-title {
        font-size: 2.5rem;
        font-weight: bold;
        letter-spacing: 2px;
        color: #0d6efd;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 8px rgba(13,110,253,0.08);
    }
    .complaints-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
        width: 100%;
        max-width: 1800px;
    }
    .complaint-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 6px 32px 0 rgba(31, 38, 135, 0.13);
        min-width: 320px;
        max-width: 340px;
        padding: 1.5rem 1.2rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
        transition: box-shadow 0.2s, transform 0.2s;
        border: 3px solid transparent;
    }
    .complaint-card.new-animate {
        animation: slideIn 0.8s cubic-bezier(.68,-0.55,.27,1.55);
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px #0d6efd33;
    }
    .complaint-card.assigned-animate {
        animation: bounceIn 0.7s;
        border-color: #ffc107;
        box-shadow: 0 0 0 4px #ffc10733;
    }
    @keyframes slideIn {
        0% { opacity: 0; transform: translateY(-60px) scale(0.95); }
        80% { opacity: 1; transform: translateY(8px) scale(1.03); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes bounceIn {
        0% { transform: scale(0.8); }
        60% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .complaint-ref {
        font-size: 1.2rem;
        font-weight: bold;
        color: #0d6efd;
        margin-bottom: 0.5rem;
    }
    .complaint-user {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 0.3rem;
    }
    .complaint-status {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        padding: 0.2rem 0.7rem;
        border-radius: 12px;
        background: #eaf1fb;
        color: #0d6efd;
        display: inline-block;
    }
    .complaint-priority {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        padding: 0.2rem 0.7rem;
        border-radius: 12px;
        background: #fff3cd;
        color: #856404;
        display: inline-block;
    }
    .complaint-meta {
        font-size: 0.98rem;
        color: #555;
        margin-bottom: 0.2rem;
    }
    .complaint-time {
        font-size: 0.95rem;
        color: #888;
        margin-top: 0.5rem;
    }
    @media (max-width: 900px) {
        .complaints-grid { gap: 1rem; }
        .complaint-card { min-width: 95vw; max-width: 98vw; }
    }
</style>
<div class="live-dashboard-container">
    <div class="dashboard-title">Live Complaints Dashboard</div>
    <div class="complaints-grid" id="complaintsGrid">
        <!-- Complaint cards will be rendered here by JS -->
    </div>
</div>
<!-- Notification Sound -->
<audio id="notifySound" src="{{ asset('sounds/notify.mp3') }}" preload="auto"></audio>
@endsection

@push('scripts')
<!-- jQuery (local) -->

<!-- <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}"> -->
<script>
// --- CONFIG ---
// Backend endpoint must return JSON array of complaints, each with at least:
// id, reference_number, user_name, status, priority, assigned_to, created_at, updated_at
// Example endpoint: route('complaints.liveData')
const DATA_URL = "{{ route('complaints.liveData') }}"; // You must create this route & controller
const POLL_INTERVAL = 5000; // ms

let lastComplaintIds = [];
let lastAssignedMap = {};

function renderComplaints(complaints) {
    const grid = $('#complaintsGrid');
    let html = '';
    complaints.forEach(c => {
        let animateClass = '';
        if (!lastComplaintIds.includes(c.id)) {
            animateClass = 'new-animate';
        } else if (lastAssignedMap[c.id] !== undefined && lastAssignedMap[c.id] !== c.assigned_to) {
            animateClass = 'assigned-animate';
        }
        html += `<div class="complaint-card ${animateClass}" data-id="${c.id}">
            <div class="complaint-ref">${c.reference_number}</div>
            <div class="complaint-user">User: ${c.user_name}</div>
            <div class="complaint-status">Status: ${c.status}</div>
            <div class="complaint-priority">Priority: ${c.priority}</div>
            <div class="complaint-meta">Assigned To: ${c.assigned_to_name || 'Not Assigned'}</div>
            <div class="complaint-time">Created: ${c.created_at}</div>
        </div>`;
    });
    grid.html(html);
    // Animate new/assigned cards
    $('.complaint-card.new-animate, .complaint-card.assigned-animate').each(function() {
        const el = $(this);
        setTimeout(() => el.removeClass('new-animate assigned-animate'), 2000);
    });
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


function pollComplaints() {
    $.get(DATA_URL, function(data) {
        // data: array of complaints
        let newIds = data.map(c => c.id);
        let assignedMap = {};
        data.forEach(c => assignedMap[c.id] = c.assigned_to);
        // Detect new or newly assigned complaints
        let isNew = false;
        data.forEach(c => {
            if (!lastComplaintIds.includes(c.id)) isNew = true;
            else if (lastAssignedMap[c.id] !== undefined && lastAssignedMap[c.id] !== c.assigned_to) isNew = true;
        });
        renderComplaints(data);
        if (isNew) playSound();
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