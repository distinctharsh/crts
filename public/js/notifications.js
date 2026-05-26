let notificationInterval;
const NOTIFICATION_INTERVAL = 1000; // 2 minutes
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
            console.error(
                'API Error:',
                response.status,
                response.statusText
            );

            return null;
        }

        const data = await response.json();

        return data;

    } catch (error) {

        console.error(
            'Fetch error:',
            error.message
        );

        return null;
    }
}

// Show popup
function showNotification(data) {

    // Safe defaults
    const unassigned = data.unassigned || 0;
    const assignToMe = data.assign_to_me || 0;

    const totalComplaints =
        unassigned + assignToMe;

    // Nothing to show

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
        <div style="display:flex;flex-direction:column;gap:10px">

            <a href="/complaints?status=${data.status_ids?.unassigned || ''}"  style="text-decoration:none;display:block;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 15px;background:#f8f9fa;border-radius:8px;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#f8f9fa'">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i class="bi bi-person-check-fill" style="color:#0d6efd;font-size:18px;"></i>
                        <span style="font-size:14px;font-weight:500;color:#333;">Unassigned Complaints</span>
                    </div>
                    <span style="font-size:22px;font-weight:700;color:#0d6efd;">${unassigned}</span>
                </div>
            </a>

            <a href="/complaints?by=${data.user_id || ''}&date_from=${data.today_date}" style="text-decoration:none;display:block;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 15px;background:#f8f9fa;border-radius:8px;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#f8f9fa'">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i class="bi bi-person-check-fill" style="color:#0d6efd;font-size:18px;"></i>
                        <span style="font-size:14px;font-weight:500;color:#333;">Assigned To Me</span>
                    </div>
                    <span style="font-size:22px;font-weight:700;color:#0d6efd;">${assignToMe}</span>
                </div>
            </a>

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