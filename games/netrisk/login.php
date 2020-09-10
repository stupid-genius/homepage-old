<?php
require_once('includes/_db.config.php');
require_once('includes/functions.php');

// Get GET and POST variables
$id = $_GET['id'];
$name = $_SESSION['player_name'];
$password = $_SESSION['player_pass'];
$player_rank = $_SESSION['player_rank'];

// Check for name and pass or else go back to join screen
if(!isset($name) or !isset($password) or !$name or !$password){
	index_error_header("You must login before playing. (login)");
	exit;
}
if(!isset($id)){ // make sure i get an id or else go to index
	index_error_header("You must be logged in to join a game.");
	exit;
}

// Get all the player info
$sql = "SELECT id,player,host,state,password,color,email FROM game_$id WHERE player = '{$name}'";
$playerinfo = get_assoc($sql);

// Get last most recent timestamp
$sql = "SELECT timestamp FROM game_$id ORDER BY 'timestamp' DESC LIMIT 1";
$timestamp = get_one($sql);
//Check username (redundancy)
if($playerinfo['player'] != $name){
	joingame_error_header("Invalid username or password for the selected game.");
	exit;
}
//WM: end my code
	
// Get game name
$sql = "SELECT name,timelimit FROM games WHERE id = $id";
$result = mysql_query($sql) or die('Could not get game name(login) :'.mysql_error());
$game_name = get_assoc($sql);

// Remove any previous Session data
session_name("netrisk_$gamename");
session_start();
// Unset session data
$_SESSION=array();
// Clear cookie
unset($_COOKIE[session_name()]);
// Destroy session data
session_destroy();
	
// Initialize the Session
session_name("netrisk_$gamename"); 
session_start();

// ONLY GET THE STATIC DATA... DYNAMIC DATA SHOULD BE LOADED BY GAME.PHP
// You cant name anything $_SESSION['host'] apparently.
$_SESSION['game_name'] = $game_name['name'];
$_SESSION['game_timelimit'] = $game_name['timelimit'];
$_SESSION['game_id'] = strval($id);
$_SESSION['player_pass'] = $password;
$_SESSION['player_id'] = $playerinfo['id'];
$_SESSION['player_rank'] = $player_rank;
$_SESSION['player_color'] = $playerinfo['color'];
$_SESSION['game_host'] = $playerinfo['host'];
$_SESSION['state'] = $playerinfo['state']; 
$_SESSION['email'] = $playerinfo['email']; 
$_SESSION['player_name'] = $playerinfo['player'];
$_SESSION['game_timestamp'] = $timestamp;




// Send to game screen now that the session has been initialized	
header("Location: game.php");
?>
