<?php
require_once('_db.config.php');
require_once('functions.php');

$id = $_SESSION['game_id'];

// make sure the player is 'inactive' status
$sql = 'SELECT state FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
$q = mysql_query($sql) or die('Could not find inactive status: '.mysql_error());
while ($row = mysql_fetch_array($q)) { $player_status = $row[0]; }
/*$player_status = $DB->getOne($sql);
if(DB::isError($player_status))
        die($player_status->getMessage());*/

if($player_status != 'inactive'){
    game_error_header("You must complete your turn before concedeing.");
        exit;
}

if (isset($_POST['concede_radio']))
{

$sql = 'UPDATE game_'.$id." SET state = 'dead' WHERE id = ".$_SESSION['player_id'];
$q = mysql_query($sql);


		// Iturzaeta - Increment Lose Value (which is now our total games value) and win by 1 point
		// Get Player Name
		$sql = 'SELECT player FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
	 	$deadplayer = get_ONE($sql);

		// Update Win (total Points)
		$sql = 'SELECT win FROM users WHERE login = \''.$deadplayer.'\'';
		$wins = mysql_query($sql) or die('Could not get number of wins from '.$deadplayer.' :' .mysql_error());
			$wins = mysql_result($wins, 0);  //only way to get actual result.  may not be correct
			$totalwins = intval($wins);
			$totalwins++;    
		// Then give that user an additional 'win'
		$sql = 'UPDATE users SET win = '.$totalwins.' WHERE login = \''.$deadplayer.'\'';
		$result = mysql_query($sql) or die('Could not ADD a win to '.$deadplayer.' total to '.$totalwins.' :' .mysql_error());

		// Update Lose (Total Games)
		$sql = 'SELECT lose FROM users WHERE login = \''.$deadplayer.'\'';
		$losses = mysql_query($sql) or die('Could not get number of wins from '.$winner.' :' .mysql_error());
			$losses = mysql_result($losses, 0);  //only way to get actual result.  may not be correct
			$totallosses = intval($losses);
			$totallosses++;    
		// Then give that user an additional 'lose'
		$sql = 'UPDATE users SET lose = '.$totallosses.' WHERE login = \''.$deadplayer.'\'';
		$result = mysql_query($sql) or die('Could not ADD a lose to '.$deadplayer.' total to '.$totallosses.' :' .mysql_error());		

		// End Of New Stats


header("Location: ../game.php");
}
else {
echo 'Please click your back button and check the concede verification box.';
exit;
}
?>
