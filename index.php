<?php
require_once __DIR__ . '/session_config.php'; 

// handling error/success messages
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
$success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herts2u | Login & Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- error/success Mmssages -->
    <?php if ($error): ?>
        <div style="color: red; text-align: center; padding: 10px; margin-bottom: 15px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="color: green; text-align: center; padding: 10px; margin-bottom: 15px;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <div class="form-col">
            <div class="btn-box">
                <button class="btn btn-1" id="Login">Sign In</button>
                <button class="btn btn-2" id="Register">Sign Up</button>
            </div>

            <!--login form with php -->
            <form class="form-box login-form" id="loginform" action="process_login.php" method="POST">
                <div class="form-title">
                    <span>Sign In</span>
                </div>
                <div class="form-inputs">
                    <div class="input-box">
                        <input type="text" name="username" class="inputs input-field" placeholder="Username" required> 
                        <ion-icon name="person-outline" class="icon"></ion-icon>
                    </div>

                    <div class="input-box">
                        <input type="password" name="password" oninput="changeIcon(this.value)" id="logPassword" class="inputs input-field" placeholder="Password" required> 
                        <ion-icon name="lock-closed-outline" class="icon" id="log-pass-icon" onclick="myLogPassword()"></ion-icon>
                    </div>
                    <div class="forgot-pass">
                        <a href="#">Forgot Password?</a>
                    </div>
                    <div class="input-box">
                        <button type="submit" class="inputs submit-btn">
                            <span>Sign In</span>
                            <ion-icon name="arrow-forward-outline"></ion-icon>
                        </button>
                    </div>
                </div>
            </form>

            <!-- register form php now -->
            <form class="form-box register-form" action="process_reg.php" method="POST">
                <div class="form-title">
                    <span>Sign Up</span>
                </div>
                <div class="form-inputs">
                    <div class="input-box">
                        <input type="email" name="email" class="inputs input-field" placeholder="Email" required> 
                        <ion-icon name="mail-outline" class="icon"></ion-icon>
                    </div>
                    <div class="input-box">
                        <input type="text" name="username" class="inputs input-field" placeholder="Username" required> 
                        <ion-icon name="person-outline" class="icon"></ion-icon>
                    </div>

                    <div class="input-box">
                        <input type="password" name="password" oninput="changeIcon(this.value)" id="regPassword" class="inputs input-field" placeholder="Password" required> 
                        <ion-icon name="lock-closed-outline" class="icon" id="reg-pass-icon" onclick="myRegPassword()"></ion-icon>
                    </div>
                    <div class="remember-me">
                        <input type="checkbox" id="remember-me-check" name="remember_me">
                        <label for="remember-me-check">Remember me</label>
                    </div>
                    <div class="input-box">
                        <button type="submit" class="inputs submit-btn">
                            <span>Sign Up</span>
                            <ion-icon name="arrow-forward-outline"></ion-icon>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>