<?php
require_once('_db.config.php');
require_once('functions.php');

//MW 04 FEB 2006: got pretty tired of 'state' overloading. changing to
// country & status.
// leaving GETs and DB table names alone

//make a copy of session
$session_copy = $_SESSION;

// save rate GET variable
$matches = array();
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';

// make sure the player is an a placement state
$sql = 'SELECT state FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];	
$player_status = get_one($sql);

if(!($player_status == 'placing' || $player_status == 'initial' || $player_status == 'forceplace')){ // strange have to use == and not !=
	game_error_header("You are not able to place armies now.");
	//header("Location: ../game.php?error=You%20are%20not%20in%20a%20Army%20Placement%20state.&$gets");
	exit;
}

//require_once('functions.php');

//MDW 061218 fix the damned cheat.
//$numarmies was set in form, then changed in function. rapidly clicking form
//submit would use a single $numarmies value multiple times, allowing cheaters
//to place craploads of troops.
//$numarmies = $_POST['numarmies']; // total armies the player has to place
$sql = 'SELECT armies FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
$numarmies = get_one($sql);
$addarmies = $_POST['armies']; // need to do number check
$tocountry = $_POST['fromstate']; // id num of state
$game_status = $_POST['gamestate']; // the current state of the player/game

if($tocountry == 'invalid'){ // didnt select a state
	game_error_header("You do not own that country.");
	//header("Location: ../game.php?display=status&error=You%20do%20not%20own%20that%20state.&$gets");
	exit;
}
if($addarmies < 0){ // error cant add negative armies
	game_error_header("There is an error with your army placement.");
	//header("Location: ../game.php?display=status&error=You%20do%20not%20have%20that%20many%20men%20to%20place.&$gets");
	exit;
}
$afterarmies = $numarmies - $addarmies;
if($afterarmies < 0){ // error tried to place to many
	game_error_header("You do not have that many armies to place.");
	//header("Location: ../game.php?display=status&error=You%20do%20not%20have%20that%20many%20men%20to%20place.&$gets");
	exit;
}

//MW 04 feb 2006: rewrote block to fix infinite loop. 
//echo '<html><head></head><body>';
// Create a new array and fill it with the players states data with the new armies

//MW 04 FEB 2006 while($a_state = current($_SESSION['STATES'])){
//echo '<html><head></head><body>';

$countries = $_SESSION['STATES'];
$newcountries = array();
//echo '<p>';
//var_dump($countries);
//echo '</p>';
while ($country = current($countries)) {
	if($country['player'] == $_SESSION['player_id']){
        //echo '<p>country<br/>';
        //var_dump($country);
        //echo '</p>';
        //echo '<p>key country<br/>';
        //var_dump(key($countries));
        //echo '</p>';
        
        //key($countries) is the index of country
        // in countries ($_SESSION['STATES'])
        //(whichever country we've currently iterated into)
		if(key($countries) == $tocountry)
			//echo '<p>armies'.$country['armies'].'</p>';
            $country['armies'] += $addarmies;
		$newcountries[] = key($countries).'+'.$country['armies'];
	}
	next($countries);
} unset($countries);//$countries should NOT be used later in code.

//MW 04 FEB 2006:should be unneccesary now reset($_SESSION['STATES']);
$newcountries = array_2_string($newcountries);



// Update the database with the new states data
//MDW 060211:
// changed to reusable function that dodges phantom armies
// function is in functions.php


if ( update_countries($DB, $_SESSION['game_id'], $_SESSION['player_id'], $newcountries) ) {
    $sql = 'UPDATE game_'.$_SESSION['game_id']." SET armies = '$afterarmies' WHERE id = ".$_SESSION['player_id']; // update their remaining total of armies in DB
    $q = single_qry($sql);
} else {
    //restore session to old good value
    $_SESSION = $session_copy;
    //do phantom armies error
    countries_error();
    exit;    
}

// start game log
$gamelog = $_SESSION['player_name']." placed ".$addarmies." armies in ".$_SESSION['STATES'][$tocountry]['name']."<hr/>";
$sql = "SELECT messages FROM game_".$_SESSION['game_id']." WHERE id = 1";
$oldlog = get_one($sql);
$totallog = $gamelog.$oldlog;
//  append the game-log with the action
$sql = "UPDATE game_".$_SESSION['game_id']." SET messages = '".$totallog."' WHERE id = 1";
$q = single_qry($sql);
// end game log

// Check if they have placed all their armies, if so go to attacking stage
if($afterarmies == 0){
	if($game_status == 'Placing Armies' || $game_status == 'Forced Placement'){
		header("Location: nextstatus.php");
		exit;
	} else { // it is the inital placement so check if regular turns can be started
		// set the current player to inactive
		$sql = 'UPDATE game_'.$_SESSION['game_id'].' SET state = \'inactive\' WHERE id = '.$_SESSION['player_id'];
        // update their remaining total of armies in DB
        // MDW: huh??
		$q = single_qry($sql);
		// check if all players are inactive, if so set the first player to their normal turn (I.E. placing (can skip trading for first turn))
		$sql = 'SELECT * FROM game_'.$_SESSION['game_id'].' WHERE state = \'initial\'';
		$q = single_qry($sql);
		if(mysql_num_rows($q) == 0){ // if there is noone in initial mode

		// Iturzaeta Random Player Code 10-09-2006
			// AND  CALL A FUNCTION TO CALCULATE HOW MANY ARMIES NEXT PERSON GETS IN PLACING AND ADD THAT TO HIS ARMIES FOR PLACEMENT STATE
			$sql = 'SELECT count(id) FROM game_'.$_SESSION['game_id'];
			$num_players = get_one($sql);
			srand((double) microtime() * 1000000);
			$next_player_id = rand(2, $num_players);
			//echo "p:$num_players n:$next_player_id\n";
			require_once('func_calculate.php');
			$sql = 'UPDATE game_'.$_SESSION['game_id']." SET state = 'trading', armies = $new_armies WHERE id = '$next_player_id' && `state` != 'dead'";
			$q = single_qry($sql);
		// update game info
		$sql = "UPDATE games SET state = 'Playing' WHERE id = ".$_SESSION['game_id'];
		$q = single_qry($sql);

		}
	}
}


header("Location: ../game.php?display=status&$gets");
?>
