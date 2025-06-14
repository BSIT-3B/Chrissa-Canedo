<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Remove the remember me cookie if it exists
if (isset($_COOKIE['remember_admin'])) {
    setcookie('remember_admin', '', time() - 3600, '/');
}

// Redirect to admin login page
header('Location: admin.php');
exit();
?>