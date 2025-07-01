<?php
require 'session_config.php';

// write test data
$_SESSION['debug_time'] = date('H:i:s');
$session_id = session_id();

echo "<h2>Session Test</h2>";
echo "<p>Session ID: $session_id</p>"; 
echo "<p>Stored data: " . $_SESSION['debug_time'] . "</p>";

// vrify database write
echo "<h3>Database Verification</h3>";
$stmt = $pdo->prepare("SELECT data FROM sessions_config WHERE session_id = ?");
$stmt->execute([$session_id]);
$db_data = $stmt->fetch();

echo "<pre>Database content: "; 
print_r($db_data);
echo "</pre>";
?>