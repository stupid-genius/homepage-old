<?
// Unset session data
$_SESSION=array();
// Clear cookie
unset($_COOKIE[session_name()]);
// Destroy session data
session_destroy(); 
// Redirect to main page
header("Location: index.php");
?>