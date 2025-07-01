<?php
session_start();
$_SESSION['user'] = 'test_user'; // force login success
header("Location: test_welcome.php");
exit();
?>