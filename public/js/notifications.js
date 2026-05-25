let notificationInterval;
const NOTIFICATION_INTERVAL = 2 * 60 * 1000; // 2 minutes
const STORAGE_KEY = 'lastNotificationShown';

// Check if notifications allowed
function isNotificationAllowed() {
    return window.canShowNotifications === true;
}

// Check if enough time has passed since last notification
function canShowNotificationNow() {
    const lastShown = localStorage.getItem(STORAGE_KEY);
    if (!lastShown) {
        return true;
    }
    const elapsed = Date.now() - parseInt(lastShown);
    return elapsed >= NOTIFICATION_INTERVAL;
}

// Store the time when notification was shown
function markNotificationShown() {
    localStorage.setItem(STORAGE_KEY, Date.now().toString());
}

// Fetch data
async function fetchNotificationData() {
    try {

        const response = await fetch('/complaints/notification-data');

        if (!response.ok) {
            throw new Error('Failed to fetch');
        }

        return await response.json();

    } catch (error) {

        console.error('Fetch error:', error);

        return null;
    }
}

// Show popup
function showNotification(data) {

    // No data → hide and exit
    if (!data) {
        hideNotification();
        return;
    }

    // Safe defaults
    const unassigned = data.unassigned || 0;
    const assignToMe = data.assign_to_me || 0;

    const totalComplaints =
        unassigned + assignToMe;

    // Nothing to show
    if (totalComplaints <= 0) {
        hideNotification();
        return;
    }

    const popup =
        document.getElementById(
            'complaintNotification'
        );

    const content =
        document.getElementById(
            'notificationContent'
        );

    // If element missing, stop
    if (!popup || !content) {
        return;
    }

    content.innerHTML = `
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;padding:12px;background:#f8f9fa;border-radius:8px;">
            <div style="width:40px;height:40px;background:#dc3545;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-exclamation-circle-fill" style="color:#fff;font-size:20px;"></i>
            </div>
            <div>
                <div style="font-size:12px;color:#6c757d;margin-bottom:2px;">Unassigned Complaints</div>
                <div style="font-size:24px;font-weight:700;color:#dc3545;">${unassigned}</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8f9fa;border-radius:8px;">
            <div style="width:40px;height:40px;background:#0d6efd;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-check-fill" style="color:#fff;font-size:20px;"></i>
            </div>
            <div>
                <div style="font-size:12px;color:#6c757d;margin-bottom:2px;">Assigned To You</div>
                <div style="font-size:24px;font-weight:700;color:#0d6efd;">${assignToMe}</div>
            </div>
        </div>
    `;

    popup.style.right = '20px';
    markNotificationShown();
}

function hideNotification() {

    const popup =
        document.getElementById(
            'complaintNotification'
        );

    if (popup) {
        popup.style.right = '-400px';
    }
}

// Main polling
async function checkNotifications() {
    const data = await fetchNotificationData();
    if (!data) {
        return;
    }

    // Only show if enough time has passed
    if (canShowNotificationNow()) {
        showNotification(data);
    }
}

// Start polling
function startNotificationPolling() {
    // Don't show immediately on page load - only after interval
    notificationInterval =
        setInterval(
            checkNotifications,
            NOTIFICATION_INTERVAL
        );
}

// Initialize
document.addEventListener(
    'DOMContentLoaded',
    function(){
        if(isNotificationAllowed()){

            console.log(
                'Starting polling...'
            );

            startNotificationPolling();
        }

    }
);

window.hideNotification = hideNotification;