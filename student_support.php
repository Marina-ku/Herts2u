<?php
// student support section
require_once __DIR__ . '/session_config.php';   // starts + validates session

//  guard: IP / UA change or 30‑min inactivity should trigger logout
if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['ip'] ?? '') !== ($_SERVER['REMOTE_ADDR'] ?? '') ||
    ($_SESSION['ua'] ?? '') !== ($_SERVER['HTTP_USER_AGENT'] ?? '') ||
    time() - ($_SESSION['last_activity'] ?? 0) > 1800
) {
    session_unset();
    session_destroy();
    header('Location: index.php?error=session_expired');
    exit();
}
$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Support | Herts2u</title>

    <!-- Global + page CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/student_support.css" />

    <!-- Ionicons -->
    <script type="module"
        src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule
        src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

<header>
    <nav class="navbar">
        <a href="welcome.php" class="nav-logo">
            <span class="logo-text">Herts2u</span>
        </a>

        <ul class="nav-menu">
            <li><a href="new_entry.php"       class="nav-link">New Entry</a></li>
            <li><a href="entries.php"         class="nav-link">Entries</a></li>
            <li><a href="student_support.php" class="nav-link">Support</a></li>
            <li><a href="logout.php"          class="nav-link">
                Logout (<?= htmlspecialchars($_SESSION['username']) ?>)
            </a></li>
        </ul>
    </nav>
</header>

<main class="support-container">

    <section class="support-hero">
        <h1>Student Support Services</h1>
        <p>We're here to help you succeed in your academic journey</p>
    </section>

    <div class="support-grid">

        <section class="support-card consent-section">
            <h2><ion-icon name="document-text-outline"></ion-icon> Journal Entry Support</h2>

            <div class="consent-toggle">
                <label>
                    <input type="checkbox" id="consentCheckbox" />
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">
                        Allow advisors to view my entries when I request support
                    </span>
                </label>
                <p class="consent-note">
                    Advisors will only access entries with your explicit permission each time.
                </p>
            </div>

            <div class="request-support">
                <h3>Request Entry Review</h3>
                <select id="entrySelect" class="form-input">
                    <option value="">Select an entry to share</option>
                    <option value="entry1">Entry #1 – May 5, 2025</option>
                    <option value="entry2">Entry #2 – May 12, 2025</option>
                </select>
                <textarea class="form-input"
                          placeholder="What would you like help with?"></textarea>
                <button class="btn-support-request">
                    <ion-icon name="send-outline"></ion-icon> Send Request
                </button>
            </div>
        </section>

        <section class="support-card quick-options">
            <h2><ion-icon name="help-buoy-outline"></ion-icon> Quick Help</h2>
            <div class="option-grid">
                <div class="option-item">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <h3>Book Appointment</h3>
                    <p>Schedule 1‑on‑1 time with an advisor</p>
                </div>
                <div class="option-item">
                    <ion-icon name="chatbubbles-outline"></ion-icon>
                    <h3>Live Chat</h3>
                    <p>Instant messaging with support staff</p>
                </div>
                <div class="option-item">
                    <ion-icon name="library-outline"></ion-icon>
                    <h3>Resources</h3>
                    <p>Self‑help guides and worksheets</p>
                </div>
                <div class="option-item">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <h3>Urgent Help</h3>
                    <p>24/7 crisis support contacts</p>
                </div>
            </div>
        </section>

        <section class="support-card advisor-notes">
            <h2><ion-icon name="people-outline"></ion-icon> Advisor Communications</h2>
            <div class="notes-container">
                <div class="note">
                    <div class="note-header">
                        <span class="note-date">May 15, 2025</span>
                        <span class="note-advisor">Dr. Adebayo</span>
                    </div>
                    <p>"I reviewed your May 12 entry and suggest we discuss time‑management strategies…"</p>
                </div>
                <div class="note new">
                    <div class="note-header">
                        <span class="note-date">Today</span>
                        <span class="note-advisor">Counseling Center</span>
                    </div>
                    <p>"Your requested appointment is confirmed for Friday at 2 pm."</p>
                </div>
            </div>
            <button class="btn-view-all">
                <ion-icon name="chevron-down-outline"></ion-icon> View All Messages
            </button>
        </section>

    </div>
</main>

</body>
</html>
