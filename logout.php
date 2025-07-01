<?php
session_start();

// clear session data
$_SESSION = array();
session_destroy();

// clear remember me cookie
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/');
}

header("Location: index.php");
exit();
?>