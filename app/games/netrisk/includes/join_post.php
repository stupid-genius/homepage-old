<?php 
require_once('_db.config.php');
require_once('player_settings.php');
require_once('functions.php');

$id = $_POST['id'];
$mailupdates = $_POST['mailupdates'];
$color = $_POST['color'];

// is color taken?

$sql = "SELECT color FROM game_$id WHERE id = 1"; // get world_data colors available
$available_colors = get_one($sql);
 	
$available_colors = string_2_array($available_colors);
if(!in_array($color, $available_colors)){
	echo "The color( ".$color.") you have chosen is within this list:\n".var_dump($available_colors)."\n, please choose again. <a href=\"joingame.php?id={$id}\">>>> Back</a>";
	exit; // is this necessary even when multiple people may be joining at once, if so do i need to do id checks also
			// may also need to do name checks
}
foreach ($available_colors as $acolor){ // do color settings before so queries are quick
	if($acolor != $color){
		$colors[] = $acolor;
	}
}
$colors_string = array_2_string($colors);

// insert Player
if (isset($mailupdates)) $updates = 1; 
// ad make sure to put into sql_data array
$sql = "INSERT INTO game_$id (player, host, state, color, mail_updates, time) VALUES ('$playername',0,'waiting','$color','$updates',NOW())";
$q = single_qry($sql);

// UPDATE world_data to REMOVE new players' color
$sql = "UPDATE game_$id SET color = '$colors_string' WHERE id = 1";	
$q = single_qry($sql);

// UPDATE games to add to players
$sql = "UPDATE games SET players = players+1 WHERE id = $id";	
$q = single_qry($sql);

// send email to host that this player has joined

// insert email verification script somewhere
// emails should be sent to player when host starts game optionally if player enters email

header("Location: ../index.php?page=gamebrowser");
// send back to page to allow login 
// .... is there a way to log player in with current login form that only accepts POST
//} else { die("This page only works with GET and POST requests."); }
?>
