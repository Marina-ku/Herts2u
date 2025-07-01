<?php
// 1. session configuration
require_once __DIR__ . '/session_config.php';

// 2. Check authentication
if (!isset($_SESSION['username'])) {
    // Store where user was trying to go
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: index.php");
    exit();
}

// 3. generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 4. security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';">
    <title>New Entry | Herts2u</title>
    
    <!-- loads css for a better performance -->
    <link rel="preload" href="assets/css/style.css" as="style">
    <link rel="preload" href="assets/css/welcome.css" as="style">
    <link rel="preload" href="assets/css/new_entry.css" as="style">
    
    <!--  CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/welcome.css">
    <link rel="stylesheet" href="assets/css/new_entry.css">
    
    <!-- favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- header -->
    <header>
        <nav class="navbar section-content">
            <a href="welcome.php" class="nav-logo">
                <h2 class="logo-text">Herts2u</h2>
            </a>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="new_entry.php" class="nav-link active">New Entry</a>
                </li>
                <li class="nav-item">
                    <a href="entries.php" class="nav-link">Entries</a>
                </li>
                <li class="nav-item">
                    <a href="student_support.php" class="nav-link">Student Support</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Log Out</a>
                </li>
            </ul>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>

    <!-- main body -->
    <main style="padding-top: 80px;">
        <section class="welcome-section">
            <div class="section-content">
                <form class="welcome-details" action="process_entry.php" method="POST" id="journalForm">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <h1 class="title">New Journal Entry</h1>
                    
                    <div class="form-group">
                        <label for="entry_title">Title</label>
                        <input type="text" id="entry_title" name="entry_title" required 
                               maxlength="100" placeholder="Entry title"
                               pattern="[A-Za-z0-9\s\-_,\.;:()]+" 
                               title="Only letters, numbers, and basic punctuation">
                    </div>
                    
                    <div class="form-group">
                        <label for="entry_content">Content</label>
                        <textarea id="entry_content" name="entry_content" rows="6" 
                                  required minlength="20" maxlength="5000"
                                  placeholder="Write your thoughts here..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="action-btn" id="submitBtn">Save Entry</button>
                        <a href="welcome.php" class="cancel-btn">Cancel</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- js (javascript validation) -->
    <script src="assets/js/form-validation.js"></script>
    <!-- mobile menu functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            const navMenu = document.querySelector('.nav-menu');
            
            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                navMenu.classList.toggle('active');
            });
            
            // Close menu when clicking on a link
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                });
            });
            
            // form submission handler 
            document.getElementById('journalForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving...';
            });
        });
    </script>
</body>
</html>