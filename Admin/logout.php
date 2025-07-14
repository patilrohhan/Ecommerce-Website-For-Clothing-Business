<?php
// Admin/logout.php
require_once 'admin_functions.php';  // This file starts the session and provides common functions

// Destroy all session data
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit();
?>
