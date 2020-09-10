<?php
//require_once("_db.master.php");

function array_2_string($array){
	return implode(',',$array);
}
function string_2_array($string){
	return explode(',', $string);
}

//MDW 060211
// move update countries to one place, add error checking code to prevent phantom armies
// player_id & new_countries can be arrays, or just one player_id & new_countries string
// returns true if success, false if error.
function update_countries($DB, $game_id, $player_id, $countries ){
/*    global $_DEBUGNETRISK;

    if($_GET['debug']) {
        $_DEBUGNETRISK['update_countries_DB'.time()] = $DB;
        $_DEBUGNETRISK['update_countries_game_id'.time()] = $game_id;
        $_DEBUGNETRISK['update_countries_player_id'.time()] = $player_id;
        $_DEBUGNETRISK['update_countries_countries'.time()] = $countries;
    }
*/

    if (substr_count($countries,'+') < 1) {
        //if countries doesn't contain at least one '+', then the value is bad and will give phantom armies.
        return false;
    }
    //$backup_armies = 'SELECT states from game_'.$game_id." WHERE id = ".$player_id;
    $sql = 'UPDATE game_'.$game_id." SET states = '$countries' WHERE id = ".$player_id; // update new states with new armies in DB
    $q = single_qry($sql);
    return true;

}

//MDW 060211
// updates armies in two countries. better maybe to roll this into update_countries,
// but i don't understand php arrays well enough to trust that in a bugfix
//MDW 060222
// added $dead variable. if $dead, extra error correction is ignored.
// first country must be attacker.
function update_two_countries($DB, $game_id, $player_one, $countries_one, $player_two, $countries_two, $player_two_dead ) {
    if (substr_count($countries_one,'+') < 1 ) {
        //if countries doesn't contain at least one '+', then the value is bad and will give phantom armies.
        return false;
    }
    if (substr_count($countries_two,'+') < 1 && !$player_two_dead) {
        //if countries doesn't contain at least one '+', then the value is bad and will give phantom armies.
        return false;
    }
    //first player
    //$backup_armies = 'SELECT states from game_'.$game_id." WHERE id = ".$player_one;
    $sql = 'UPDATE game_'.$game_id." SET states = '$countries_one' WHERE id = ".$player_one;
    $q = single_qry($sql);
    //second player
    //$backup_armies = 'SELECT states from game_'.$game_id." WHERE id = ".$player_two;
    $sql = 'UPDATE game_'.$game_id." SET states = '$countries_two' WHERE id = $player_two";
    $q = single_qry($sql);
    return true;
}

//MDW 060211
// to be called by originating script when update_(two_)countries encounters an error
function countries_error(){
    game_error_header("Phantom armies. Some recent information may be lost. Please reload and try again."); 
    //header("Location: ../game.php?display=status&error=$error&$gets");
    //originating script should call exit
}


//MDW 060211
// changes 'you are here' to 'you%20are%20here'
function error_text($text){
    $proper = str_replace(' ','%20',$text); 
    //test this later.
    //htmlentities($proper);
    return $proper;
}
//MDW 060211
// calls an error header only for game.php
// originating script should call exit
// -------
// nec header variables

$host = $_SERVER['HTTP_HOST'];
$root = "http://".$host.$gamepath;

function game_error_header($error){
	    global $root, $gamepath, $host;
    $nerror = error_text($error); 
    header("Location: ".$root."game.php?display=status&error=$nerror&$gets");
    //originating script should call exit    
}
// JHD 060223
// error header for index page.
function index_error_header($error){
	    global $root, $gamepath, $host;
	$nerror = error_text($error);
	header("Location: ".$root."index.php?error=$nerror&$gets");
}
// JHD 060223
// need a function that handles joingame.php better.
function joingame_error_header($error){
	    global $root, $gamepath, $host;
	$id = $_GET['id'];  //must set id as variable
	$nerror = error_text($error);
    /*
    echo "root: ".$root."<br/>".$nerror;
    echo "<br/>host: ".$host."<br/>"."gamepath".$gamepath;
    echo "<br/>"."http-host".$_SERVER['HTTP_HOST'];
    */
    //echo "Location: ".$root."joingame.php?id=$id&error=$nerror&$gets";
	header("Location: ".$root."joingame.php?id=$id&error=$nerror&$gets");

}

//MOVED FROM GAME.PHP *********************
function game_state() {
// query games database for game state (playing or waiting)
$sql = 'SELECT state FROM games WHERE id = '.$_SESSION['game_id'];
$gamestate = mysql_query($sql) or die ('Could not query DB for game state.' .mysql_error());

// if waiting give user access only to admin and message and (next status ie Start Game for Host)
switch($gamestate){  //   !!!!!!!!!! GAME STATE DEPENDS ON OVERALL GAMES STATE AND PLAYERS STATE
	case 'Waiting':	$gamestate = 'Waiting for Start'; // Waiting for Start
					break;
	case 'Finished': $gamestate = 'Finished'; // Game Over
					break;
	default: player_state();  // FOR ANY STATE WHEN PLAY IS GOING ON, EXCLUDES GAME OVER OR NOT YET BEGUN
			break;
}
return $gamestate;
}
// MOVED FROM GAME.PHP **************
// For when game is in Play
function player_state(){ // eh dont like globals........................
	global $DB, $gamestate;
	$sql = 'SELECT state FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id']; // Get player state
	$playerstate = get_one($sql);
	switch($playerstate){  //   !!!!!!!!!! GAME STATE DEPENDS ON OVERALL GAMES STATE AND PLAYERS STATE
	// which pages to activate should be activated here in a default variable as opposed to if statements currently implemented	
		case 'waiting': $gamestate = 'Waiting for Start';   //Added, trying to fix. JD
					break;
		case 'inactive': $gamestate = 'Waiting for Players'; // Player -> Start (change)
					break;
		case 'trading': $gamestate = 'Trading Cards';
					break;
		case 'placing': $gamestate = 'Placing Armies';
					break;
		case 'attacking': $gamestate = 'Attacking';
					break;
		case 'fortifying': $gamestate = 'Fortifying';
					break;
		case 'initial': $gamestate = 'Initial Placement';
					break;
		case 'dead': $gamestate = 'Defeated'; // do something special here?
					break;
		case 'forcetrade': $gamestate = 'Forced Trading Cards';
					break;
		case 'forceplace': $gamestate = 'Forced Placement';
					break;			
		case 'winner': $gamestate = 'Game Over';
					break;
		default: $gamestate = 'Unknown';
				break;
	}
    return $gamestate;    
}

// *********  skip player's turn *************
function player_time_limit(){
        global $_DEBUGNETRISK;

        // Find out if the game has a time limit.
	$sql ="SELECT timelimit from games WHERE id = ".$_SESSION['game_id'];
	$gamelimit = get_one($sql);

        if (!$gamelimit) { return; }
	
        //Find out if the game has started yet.
	$sql ="SELECT state FROM games WHERE id = ".$_SESSION['game_id'];
	$gstatus = get_one($sql);

        // If status is Playing, check the timelimit
        if ($gstatus != 'Playing') { return; }	

        // find out who's turn it is
	$sql = 'SELECT id FROM game_'.$_SESSION['game_id'].' WHERE state != \'inactive\' AND state != \'initial\' AND state != \'dead\' AND state != \'waiting\' AND id != 1';
	$current_id = get_one($sql);
$_DEBUGNETRISK['current_id'] = $current_id;	
	if ($current_id == 0) { return;  }

/*
	if ($current_id == 2) { 
		$sql ="SELECT players FROM games WHERE id = ".$_SESSION['game_id'];
		$q = get_one($sql);
		$last_id = $q + 1;
	} else {
                $last_id = $current_id - 1;
        }
        // get LAST player's time
	$sql = "SELECT timestamp FROM game_".$_SESSION['game_id']." WHERE id = ".$last_id;
	$lastmove = get_one($sql);
*/
        //find out when last move was made by player marked 'active'
	$sql = "SELECT timestamp FROM game_".$_SESSION['game_id']." WHERE id = ".$current_id;
	$lastmove = get_one($sql);

        // Then find out the game's time-limit.
	$sql = "SELECT timelimit from games WHERE id = ".$_SESSION['game_id'];
	$timelimit = get_one($sql);

        // Compare timestamps based on time limit
        $now = time();
        $timeout = $now - $lastmove;

        $_DEBUGNETRISK['lastmove'] = $lastmove;
        $_DEBUGNETRISK['timelimit'] = $timelimit;
        $_DEBUGNETRISK['timeout'] = $timeout;

        if ($timeout > $timelimit) {
	        // skip player's turn
	        $sql = "UPDATE game_".$_SESSION['game_id']." SET state = 'inactive' WHERE id = ".$current_id;
	        $q = single_qry($sql);

        	// give next player the turn
	        $sql = 'SELECT players FROM games WHERE id = '.$_SESSION['game_id'];
	        $numplayers = get_one($sql);
                $numplayers++;

        	$next_player_id = $current_id +1;
        $_DEBUGNETRISK['numplayers'] = $numplayers;
	        while(1){ // loop until a valid next player is found
		        if ($next_player_id > $numplayers) { 
                                $next_player_id = 2;
                        }
		
		        // make sure the next player is not dead
		        $sql = 'SELECT state FROM game_'.$_SESSION['game_id']." WHERE id = $next_player_id";
		        $next_players_state = get_one($sql);

		        if($next_players_state == 'inactive'){
			        break;
		        }	

        		$next_player_id++;
	        }
        $_DEBUGNETRISK['next_player'] = $next_player;
                require_once('func_calculate.php');

	        // update next players state and add their new armies
        	$sql = 'UPDATE game_'.$_SESSION['game_id']." SET state = 'trading', armies = $new_armies, timestamp = $now WHERE id = $next_player_id";
	        $q = single_qry($sql);
	}	
}
function timestamp_player() {
	$tstamp = time();
	$sql = "UPDATE game_".$_SESSION['game_id']." SET timestamp = ".$tstamp." WHERE id = ".$_SESSION['player_id'];
	$q = single_qry($sql);	
}

// Check to see if anyone is in initial status
function check_Initial(){
	global $DB, $game_id, $gamestate;
	$sql = "SELECT state FROM game_$game_id WHERE state = 'initial'";
	$playerstate = single_qry($sql);
	if(mysql_num_rows($playerstate) > 0){ // a player is still in initial placement state
		$gamestate = 'Initial Placement';
	}
}

function check_if_gameover() {
	/************************************************/
/********************CHECKING for Winner********/
// check if conqueror has won the game
		$sql = 'SELECT state FROM game_'.$_SESSION['game_id'].' WHERE id > 1 AND state != \'dead\'';
		$q = single_qry($sql);
	// if more than one row the game is not over
		if(mysql_num_rows($q) == 1){ // only one person left, then GAME OVER
			$sql = 'UPDATE games SET state = \'Finished\' WHERE id = '.$_SESSION['game_id'];
			$q = single_qry($sql);	
/*Add a 'win' to the winners*/
	// first, find out who the winner is.
		$sql = 'SELECT player FROM game_'.$_SESSION['game_id'].' WHERE id > 1 AND state != \'dead\'';
	 	$winner_p = get_one($sql);
	// Get current number of 'win's, then add 1.
		$sql = 'SELECT win FROM users WHERE login = \''.$winner_p.'\'';
		$totalwins = get_one($sql);
			$totalwins++;    
	// Then give that user an additional 'win'
		$sql = 'UPDATE users SET win = '.$totalwins.' WHERE login = \''.$winner_p.'\'';
		$result = single_qry($sql);
/*End add a 'win' */	
	// Set the winner's state to winner
		$sql = 'UPDATE game_'.$_SESSION['game_id'].' SET state = \'winner\' WHERE player = \''.$winner_p.'\'';
		$result = single_qry($sql);
  // Set game Status to finished
  	$sql = 'UPDATE games SET state = \'Finished\', mode = \''.$winner.'\' WHERE id = '.$_SESSION['game_id'];
		$q = single_qry($sql);	
		return true; 
		} else { 
		return false; 
		} 
/*****************End, checking for winner ********** */
}

function seconds_to_HMS($time_in_secs)
{       
   $secs = $time_in_secs % 60;
   $time_in_secs -= $secs;
   $time_in_secs /= 60;
  
   $mins = $time_in_secs % 60;
   $time_in_secs -= $mins;
   $time_in_secs /= 60;
  
   $hours = $time_in_secs;  

   return str_pad($hours,2,'0',STR_PAD_LEFT) . "h" . str_pad($mins,2,'0',STR_PAD_LEFT) . "m" . str_pad($secs,2,'0',STR_PAD_LEFT) . "s";
}

function seconds_to_DHMS($time_in_secs)
{       
   $secs = $time_in_secs % 60;
   $time_in_secs -= $secs;
   $time_in_secs /= 60;
  
   $mins = $time_in_secs % 60;
   $time_in_secs -= $mins;
   $time_in_secs /= 60;
  
   $hours = $time_in_secs % 24;
   $time_in_secs -= $hours;
   $time_in_secs /=24;
   
   $days = $time_in_secs;
   if ($days <= 1) {
	   return str_pad($hours,2,'0',STR_PAD_LEFT) . "h " . str_pad($mins,2,'0',STR_PAD_LEFT) . "m " . str_pad($secs,2,'0',STR_PAD_LEFT) . "s";
   } else {
	   return str_pad($days,2,'0',STR_PAD_LECT) . "d " . str_pad($hours,2,'0',STR_PAD_LEFT) . "h " . str_pad($mins,2,'0',STR_PAD_LEFT) . "m";
   }
}

/* ********* html to txt *******/

function html2txt($document){
$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
               '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
);
$text = preg_replace($search, '', $document);
return $text;
}

/* MDW 061028 randomstuffs */
function dice_roll( )
{
//rcon & gothmogs:
	mt_srand((double) microtime() * 1000000);
//benjams:
  //usleep(rand(1, 10000)); // pause the script for a short but random period
  //list($usec, $sec) = explode(' ', microtime( ));
  //mt_srand( (float) $sec + ((float) $usec * 100000) );
  return mt_rand(1,6);

}

?>
