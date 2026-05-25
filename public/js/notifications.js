let notificationInterval;

// Check if notifications allowed
function isNotificationAllowed() {
    return window.canShowNotifications === true;
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
        <p><strong>Unassigned:</strong> ${unassigned}</p>
        <p><strong>Assigned To Me:</strong> ${assignToMe}</p>
    `;

    popup.style.right = '20px';
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

    showNotification(data);
}

// Start polling
function startNotificationPolling() {
    checkNotifications();
    notificationInterval =
        setInterval(
            checkNotifications,
            2 * 60 * 1000
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