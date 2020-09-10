<?php
require_once('_db.config.php');
require_once('functions.php');

/* JD: I had to add this to make registration work.  for some reason it trys to go to user_login.php after 
registering.   This check sends it back to insert the new user.*/
if (isset($_POST['reg_log'])){
	require_once("../admin/insert_user.php");
	exit;
}


// Get GET and POST variables
$id = $_POST['id'];
$name = $_POST['login'];
$password = md5($_POST['pass']);

// Check for name and pass or else go back to join screen
if(!isset($name) or !isset($password) or !$name or !$password){
/*MW: changed to amp entity*/
	joingame_error_header("You must specify a username to login.");	
	exit;
}
if(!isset($id)){ // make sure i get an id or else go to index	
	index_error_header("ID is not set. You can not join a game.");
	exit;
}
// Get all the player info
$sql = "SELECT id,rank,login,pass,email,bio,avatar FROM users WHERE login = '{$name}'";
$playerinfo = get_assoc($sql);

// Check password
if($playerinfo['pass'] != $password){
	index_error_header("Invalid username or password.");
	exit;
}
// JD: SESSION info that is to be passed back to index page.
$_SESSION['player_pass'] = $password;
$_SESSION['player_id'] = $playerinfo['id'];
$_SESSION['player_name'] = $playerinfo['login'];
$_SESSION['player_rank'] = $playerinfo['rank'];
$_SESSION['player_email'] = $playerinfo['email'];
$_SESSION['player_bio'] = $playerinfo['bio'];
$_SESSION['player_avatar'] = $playerinfo['avatar'];
//$_SESSION['diplomacy'] = $playerinfo['diplomacy'];
//$_SESSION['portrait'] = $playerinfo['portrait']; // for use on personal settings page
// dont need mail_updates....

// Send to game screen now that the session has been initialized	
header("Location: ../index.php?page=gamebrowser");
?>
