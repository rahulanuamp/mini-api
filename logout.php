<?php
session_start();

/* Remove all session data */
$_SESSION = [];

/* Destroy session */
session_destroy();

/* Redirect to Sign In page */
header("Location: signin.php");
exit;
?>