<?php
require_once('_db.config.php');
if(ini_get('magic_quotes_gpc')){ // if magic quotes is enabled dont add slashes
	$playername = $_SESSION['player_name']; 
} else {
	$playername = addslashes($_SESSION['player_name']); 
}
$color = $_POST['color'];
$playerpassword = $_SESSION['player_pass']; 
$portraiturl = ''; //$_POST['portrait']; // placeholder for un-implemented feature
$email = $_POST['email']; // insert email verification script here
if ($email) $updates = 1; else $updates = 0;
?>
