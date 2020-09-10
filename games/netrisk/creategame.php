<?php
require_once("includes/_db.config.php");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	include_once('includes/newgame.php');  // Run New Game form
} else if($_SERVER['REQUEST_METHOD'] == 'POST'){
	include_once('includes/create.php');  // Run Game Creation Using Game form info
} else { die("This page only works with GET and POST requests."); }

// emails should be sent to host each time a player joins his game

// I 'include' from here but 'include' of included files dont seem to have to reference the 'includes' folder to work....
?>
