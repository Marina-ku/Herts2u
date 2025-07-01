<?php
require_once __DIR__ . '/session_config.php'; 
require 'db_connect.php';

// auto-logout
if (!isset($_SESSION['user_id']) ||
    $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
    $_SESSION['ua'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '') ||
    time() - $_SESSION['last_activity'] > 1800) {

    session_unset();
    session_destroy();
    header("Location: index.php?error=session_expired");
    exit();
}

// update activity timestamp
$_SESSION['last_activity'] = time();

// get user data
$stmt = $pdo->prepare("SELECT email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Herts2u</title>
    <link rel="stylesheet" href="assets/css/welcome.css">
    <script>
    let timeoutWarning = setTimeout(function() {
        alert('Your session will expire in 2 minutes due to inactivity.');
    }, 1500000); // 25 minutes
    </script>
</head>
<body>
    <!-- navigation area -->
    <header>
        <nav class="navbar section-content">
            <a href="welcome.php" class="nav-logo">
                <h2 class="logo-text">Herts2u</h2>
            </a>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="new_entry.php" class="nav-link">New Entry</a>
                </li>
                <li class="nav-item">
                    <a href="entries.php" class="nav-link">Entries</a>
                </li>
                <li class="nav-item">
                    <a href="student_support.php" class="nav-link">Support</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- dashboard cotnet -->
    <main class="welcome-section">
        <div class="section-content">
            <div class="welcome-details">
                <h1 class="title">Welcome to Herts2u</h1>
                <h2 class="subtitle">Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
                
                <!-- Dashboard Cards -->
                <div class="dashboard-cards">
                    <div class="dashboard-card user-card">
                        <h3>Your Profile</h3>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                        <p><strong>Member since:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
                        <p><strong>Last activity:</strong> <span id="activity-timer"></span></p>
                    </div>
                    
                    <div class="dashboard-card quick-actions">
                        <h3>Quick Actions</h3>
                        <a href="new_entry.php" class="action-btn">Create New Entry</a>
                        <a href="entries.php" class="action-btn">View All Entries</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    // session timer display
    document.addEventListener('DOMContentLoaded', function() {
        let lastActivity = <?= $_SESSION['last_activity'] ?>;
        
        function updateTimer() {
            const now = Math.floor(Date.now() / 1000);
            const elapsed = now - lastActivity;
            const remaining = 1800 - elapsed; // 30 minutes
            
            if (remaining <= 0) {
                window.location.href = 'logout.php?timeout=1';
                return;
            }
            
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            document.getElementById('activity-timer').textContent = 
                `${minutes}m ${seconds}s remaining`;
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
        
        ['click', 'keypress', 'mousemove'].forEach(event => {
            window.addEventListener(event, () => {
                fetch('ping.php').catch(() => {}); // silent 
            });
        });
    });
    </script>
</body>
</html>
