<?php // Switches player status' depending on current players status
//mw 04 feb 2006: fixed to work with calculate armies again. 
//did not change any other variable names.
require_once('_db.config.php');
require_once('functions.php');

// save rate GET variable
$matches = array();
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';


if (check_if_gameover()) { header("Location: ../game.php?$gets");
exit;}

$sql = 'SELECT state FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
$current_state = get_one($sql);

// Check State advancement requirements
if($current_state == 'placing' || $current_state == 'initial' || $current_state == 'forceplace'){
	// check if placing state requirements are met
	$sql = 'SELECT armies FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
	$current_armies = get_one($sql);

	if($current_armies != 0){ // player must place remaining armies
		game_error_header("You must place all armies before continuing");
		exit;
	}
} else if($current_state == 'trading' || $current_state == 'forcetrade'){
	// check if trading state requirements are met
	$sql = 'SELECT cards FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
	$current_cards = get_one($sql);
	$current_cards = string_2_array($current_cards);
	if(count($current_cards) > 4){ // player must trade as they have 5 or more cards
		game_error_header("You must trade in cards until you have less than five.");
		exit;
	}
}

$tstamp = time();

$next_player = false;
switch($current_state){
	case 'placing': $next_state = 'attacking';
						break;
	case 'attacking': $next_state = 'fortifying';
						// set players attackcard to 0
		$sql = 'UPDATE game_'.$_SESSION['game_id'].' SET attackcard = 0 WHERE id = '.$_SESSION['player_id'];
		$q = single_qry($sql);
						break;
	case 'fortifying': $next_state = 'inactive';
			$gamelog = $_SESSION['player_name']." ended the turn. <hr />";
						$next_player = true;
						break;
	case 'trading': $next_state = 'placing';
						break;
	case 'initial': $next_state = 'inactive';
			$gamelog = $_SESSION['player_name']." placed his initial armies. <hr />";
		$sql = "UPDATE game_".$_SESSION['game_id']." SET timestamp = ".$tstamp." WHERE id = ".$_SESSION['player_id'];
		$q = single_qry($sql);

						break;
	case 'forcetrade': $next_state = 'forceplace';
						break;					
	case 'forceplace': $next_state = 'attacking';
						break;		
}

// Update player activity time for time limit
//$sql = 'UPDATE game_'.$_SESSION['game_id']." SET timestamp = '{$tstamp}' WHERE id = ".$_SESSION['player_id'];
$sql = 'UPDATE game_'.$_SESSION['game_id']." SET timestamp = '{$tstamp}' WHERE 1";
$q = single_qry($sql);

// Update time on games table
$now = date("Y-m-d H:i:s");
$sql = "UPDATE games SET time = '{$now}' WHERE id = ".$_SESSION['game_id'];
$q = single_qry($sql);

// update current players state in DB
$sql = 'UPDATE game_'.$_SESSION['game_id']." SET state = '$next_state' WHERE id = ".$_SESSION['player_id'];
$q = single_qry($sql);

// check if another players state needs to be changed
if($next_player){
	$sql = 'SELECT players FROM games WHERE id = '.$_SESSION['game_id'];
	$numplayers = get_one($sql);
		
	$next_player_id = $_SESSION['player_id'] + 1; 
	while(1){ // loop until a valid next player is found
	// make sure the next_player_id is a valid player
		if($next_player_id > ($numplayers+1)) // reset player id to 1
			$next_player_id = 2;	
		// make sure the next player is not dead
		$sql = 'SELECT state FROM game_'.$_SESSION['game_id']." WHERE id = $next_player_id";
		$next_players_state = get_one($sql);

		if($next_players_state != 'dead' && $next_players_state != 'unused'){
			break;
		}	
		$next_player_id++;
	}	
	
	// next_player_id is used by calculate_armies to decide who to calculate for
	require_once('func_calculate.php');
	// update next players state and add their new armies
	$sql = 'UPDATE game_'.$_SESSION['game_id']." SET state = 'trading', armies = $new_armies WHERE id = $next_player_id";
	$q = single_qry($sql);

	// EMAIL next PLAYER
	// Find out who next player is
	$sql = "SELECT player FROM game_".$_SESSION['game_id']." WHERE state = 'trading'";
	$emailplayer = get_one($sql);
	
	// Find out if current player wants emails.
	$sql = "SELECT mail_updates FROM game_".$_SESSION['game_id']." WHERE player = '".$emailplayer."'";
	$updates = get_one($sql);
    
    if ($updates=='1') {
        
	    // Email that player if they have selected to recieve emails
	    $sql = "SELECT email FROM users WHERE login = '".$emailplayer."'";
	    $rs = single_qry($sql);
	    while ( $row = mysql_fetch_array( $rs )) {
	    	$to = $row[0];
        
	    $subject = "Game Update: ".$_SESSION['game_name'];
	    $body = "It is now your turn in the netrisk game.";
	    $header = 'From: NetRisk <'.$adminemail.'>'; // otherwise, it will send it as the servers username
	    	mail($to, $subject, $body, $header);
	    	}
        
	}
	
}
// start game log
$sql = "SELECT messages FROM game_".$_SESSION['game_id']." WHERE id = '1'";
$oldlog = get_one($sql);
$totallog = $gamelog.$oldlog;
//  append the game-log with the action
$sql = "UPDATE game_".$_SESSION['game_id']." SET messages = '".$totallog."' WHERE id = '1'";
$q = single_qry($sql);
// end game log stuff

header("Location: ../game.php?$gets");
?>
