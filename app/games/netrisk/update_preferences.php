<?php	
require_once('includes/_db.config.php');

// Changes session and DB values while storing for query
$pass = $_POST['playerpassword1'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['portrait'] = $_POST['portrait']; // not even used...

// Create query
$sql = "UPDATE game_$game_id SET ";
if($pass) // if they want to update passwords use a different sql query
	$sql .= "password = '".md5($pass)."', ";
if($email)
	$sql .= "mail_updates = 1, "; // more stuff go here depending on notifications
else
	$sql .= "mail_updates = 0, ";
$sql .= "email = '$email', portrait = '".$_SESSION_['portrait'].'\' WHERE id = '.$_SESSION['player_id'];
// run query
$q = mysql_query($sql) or die('Cant update player profile: '.mysql_error());
    		
// save rate GET variable
$matches = array();
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';
header("Location: game.php?$gets");
?>
