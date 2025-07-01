<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


require 'session_config.php';
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //  inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        header("Location: index.php?error=Username+and+password+are+required");
        exit();
    }

    try {
        // Checks user credentials
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // destroy any existing session
            session_destroy();
            
            // start fresh secure session
            session_start([
                'use_strict_mode' => true,
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax'
            ]);
            
            // regenerate ID before setting data
            session_regenerate_id(true);
            
            // set session data
            $_SESSION = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'last_activity' => time(),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'ua' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'created' => time()
            ];

            // Remember me functionality is optionall
            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, [
                    'expires' => time() + 60*60*24*30,
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
                // stores hashed token in database
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                $updateStmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $updateStmt->execute([$hashedToken, $user['id']]);
            }

            // makes sure session was saved
            if (isset($_SESSION['user_id'])) {
                header("Location: welcome.php");
                exit();
            } else {
                throw new Exception("Session data not saved");
            }
        } else {
            header("Location: index.php?error=Invalid+credentials");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: index.php?error=System+error");
        exit();
    } catch (Exception $e) {
        error_log("Session error: " . $e->getMessage());
        header("Location: index.php?error=Session+error");
        exit();
    }
} else {
    // Non-POST request
    header("Location: index.php");
    exit();
}
?>