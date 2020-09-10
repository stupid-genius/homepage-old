<?php
//mw 04 feb 2006: trying to fix attacking. unoverloading 'state'
require_once('_db.config.php');
require_once('functions.php');

//make a copy of $_SESSION
$session_copy = $_SESSION;

// save rate GET variable
$matches = array(); 
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';

// make sure the player is attacking state
$sql = 'SELECT state FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];	
$player_status = get_one($sql);
if($player_status != 'attacking'){
	game_error_header("You cannot attack at this time.");
	exit;
}	



$attacking_armies = $_POST['armies']; // need to do number check

//MDW 060204
$to_country_info = string_2_array($_POST['tostate']);
$from_country_info = string_2_array($_POST['fromstate']);

$to_country = $to_country_info[0]; //state ids
$from_country = $from_country_info[0];

$to_country_selectnum = $to_country_info[1]; //state select # in the select tag
$from_country_selectnum = $from_country_info[1];

unset($to_country_info);
unset($from_country_info);

if($to_country == 'invalid' || $from_country == 'invalid'){
	game_error_header("Invalid country selection.");
	exit;
}
if($attacking_armies < 1){ // error cant add negative armies
	game_error_header("You need more armies to attack.");
	exit;
}

////// CHECK IF tostate AND fromstate ARE ADJACENT ///////////////////
$sql = "SELECT adjacencies FROM countries WHERE id = '$from_country'";
$country_adjacencies = get_one($sql);
$country_adjacencies = string_2_array($country_adjacencies);
if(!in_array($to_country, $country_adjacencies)){
	game_error_header("Those countries are not adjacent.");
	exit;
}


$available_armies = $_SESSION['STATES'][$from_country]['armies'];
$opposing_armies = $_SESSION['STATES'][$to_country]['armies'];
$opposing_id = $_SESSION['STATES'][$to_country]['player'];

$attacking_armies = min($attacking_armies, 3);// more than two assume they meant to attack with 3 withc is max anyways for rolling	
if (($available_armies - $attacking_armies) < 1){ // they dont have enough to guard the launching state after attacking
	game_error_header("You do not have that many attacking armies.");
		exit;
}

// NOW DO BATTLE
$attackrolls = array(0,0,0);
$defendrolls = array(0,0);

// reset Session data
$_SESSION['defend_rolls'] = $defendrolls;
$_SESSION['attack_rolls'] = $attackrolls;

// generate first roll

$defendrolls[0] = dice_roll( );
$attackrolls[0] = dice_roll( );

// generate additional rolls if necessary
if($opposing_armies > 1)
{
	$defendrolls[1] = dice_roll( ); // default defend with 2 which is max if there are 2
}
if($attacking_armies > 1)
{ 
	$attackrolls[1] = dice_roll( ); // generate 2nd attack roll
	if($attacking_armies > 2) 
	{
		$attackrolls[2] = dice_roll( ); // generate 3rd attack roll
	}
}

rsort($attackrolls); // sort highest to lowest
rsort($defendrolls);

// Save the die rolls as Session data
$_SESSION['defend_rolls'] = $defendrolls;
$_SESSION['attack_rolls'] = $attackrolls;

$attackcasualties = 0;
$defendcasualties = 0;

$fighters = 1; // keeps track of what rolls are fighting, subtract 1 to get at correct array index
while($fighters <= 2 && $fighters <= $opposing_armies && $fighters <= $attacking_armies){ // to keep 0's from engaging // 2 instead of attack armies cause
	if($attackrolls[$fighters-1] > $defendrolls[$fighters-1])	// you cant have 3 fighting ever in Risk
		$defendcasualties -= 1;
	else
		$attackcasualties -= 1;
	$fighters++;
}
$opposing_armies += $defendcasualties; // subtract casualties
$available_armies += $attackcasualties;



$_SESSION['STATES'][$from_country]['armies'] = $available_armies;
$_SESSION['STATES'][$to_country]['armies'] = $opposing_armies;

/////////////////////////////////////////////////////////////////////
// If defenders defeated GOTO occupation and/or give card or end game
/////////////////////////////////////////////////////////////////////
if (!$opposing_armies){
	// remove state from opposer and add to conqueror
	$_SESSION['STATES'][$to_country]['player'] = $_SESSION['player_id'];
	// CHECK IF CONQUERED PLAYER IS DEAD AND ACT ACCORDINGLY
	$opposing_player_dead = true; // dead til proven alive

    $countries=$_SESSION['STATES'];
	foreach($countries as $country){
		if($country['player'] == $opposing_id){
			$opposing_player_dead = false;
			break;
		}			
	} unset($countries);
    reset($_SESSION['STATES']); // fixes disappearing states issue MDW 04 FEB 2006: maybe unneccesary now.
	
	if($opposing_player_dead){ 
    // if 0 states, player is dead
    // update his state
    $sql = 'UPDATE game_'.$_SESSION['game_id']." SET state = 'dead' WHERE id = $opposing_id"; // need to add necessary condition to game.php(status.php)
		$q = single_qry($sql);

    if (check_if_gameover()) { 
	header('Location: nextstatus.php'); 
      } else { 
      // Game NOT over but the player has been conquered so transfer all his cards to the conqueror			
			$sql = 'SELECT cards FROM game_'.$_SESSION['game_id']." WHERE id = $opposing_id";
			$enemy_cards = get_one($sql);				
			$sql = 'SELECT cards FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id']; // get card data
			$pcards = get_one($sql);
      
 		// Iturzaeta - Increment Lose Value (which is now our total games value) and win by 1 point
		// Get Player Name
		$sql = 'SELECT player FROM game_'.$_SESSION['game_id'].' WHERE id = '.$opposing_id.'';
	 	$deadplayer = get_ONE($sql);
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

      
			// add enemies cards to conquerors, dont remove cards from dead person, unnecessary since they are dead...
			if($enemy_cards!="") {
			  //MDW 061017 another if statement! for if
			  //things handled:
			  //no one has cards (do nothing)
			  //everyone has cards (pcards .=ecards)
			  //enemy has no cards, but player has cards (do nothing)
			  //things handled: enemy has no cards, but player has cards. (pcards = ecards) (fixed in this update)
			  if($pcards == "") {
			    $pcards = $enemy_cards;
		  	  }else {
            $pcards .= ','.$enemy_cards; 
          }
			}
			    
          $sql = 'UPDATE game_'.$_SESSION['game_id']." SET cards = '$pcards' WHERE id = ".$_SESSION['player_id'];
          $q = single_qry($sql);
			/// take away opponents cards
			    $sql = 'UPDATE game_'.$_SESSION['game_id']." SET cards = '' WHERE id = ".$opposing_id;
          $q = single_qry($sql);
			// + 1 if its his first conquer this turn
			    require_once('func_cards.php');			
			// now check if its necessary to force the player to turn in cards
			    $pcards  = string_2_array($pcards); // variable made by cardhandout.php - contains the players cards
            if(count($pcards) > 5){ 
				// go to trade form with special state
				    $sql = 'UPDATE game_'.$_SESSION['game_id'].' SET state = \'forcetrade\' WHERE id = '.$_SESSION['player_id'];
            $q = single_qry($sql);
			}			
		}


	} else { // PLAYER NOT DEAD SO check if the conqueror gets a card for the turn or if he already has it
// start game log
$gamelog = $_SESSION['player_name']." conquered ".$_SESSION['STATES'][$to_country]['name']."<hr />";
$sql = "SELECT messages FROM game_".$_SESSION['game_id']." WHERE id = 1";
$oldlog = get_one($sql);
$totallog = $gamelog.$oldlog;
//  append the game-log with the action
$sql = "UPDATE game_".$_SESSION['game_id']." SET messages = '".$totallog."' WHERE id = 1";
$q = single_qry($sql);
// end game log stuff
		require_once('func_cards.php');
	}
	// Create Session data for occupation form
		// state conquered id, number of armies player can occupy with,  players states, 
				// how many armies the player must occupy with and the conquering state

    $conquer_data = array($to_country, $available_armies-1, $attacking_armies, $from_country);
	$_SESSION['conquer_data'] = $conquer_data; // register conquer data for use by occupation form
}

// Create DB values for new players states post battle
$from_countries_array = array();
$to_countries_array = array();

//MDW 04 feb 2006: another spontaneous infinite loop
$countries = $_SESSION['STATES'];
while($country = current($countries)){ 
	if($country['player'] == $_SESSION['player_id']){
		$from_countries_array[] = key($countries).'+'.$country['armies'];
	} else if($country['player'] == $opposing_id){
		$to_countries_array[] = key($countries).'+'.$country['armies'];
	}
	next($countries);
} unset($countries);



$countries = array_2_string($from_countries_array);
$opposing_countries = array_2_string($to_countries_array);

// update DB with battle results
//MDW 060211: 
// made reusable function, with error conditional. should dodge phantom armies
// function is in function.php
//$countries = 'hellothere. im trying to break you';

if (update_two_countries($DB, $_SESSION['game_id'], $_SESSION['player_id'], $countries, $opposing_id, $opposing_countries, $opposing_player_dead)) {
    header("Location: ../game.php?display=status&tostate={$to_country_selectnum}&fromstate={$from_country_selectnum}&armies={$attacking_armies}&$gets");
}
else {
    //restore session to original state
    $_SESSION = $session_copy;
    //display "you've got phantom armies!"
    countries_error();
    //more session info needs to reset if this occurs. as is, player is trying to occupy country they don't own,
    //and loses armies.
    
    
    exit;
}
/*
if (substr_count($countries,'+') > 0 && substr_count($opposing_countries,'+') > 0 ) {
    $sql = 'UPDATE game_'.$_SESSION['game_id']." SET states = '$countries' WHERE id = ".$_SESSION['player_id'];
    $q = $DB->query($sql);
    if(DB::isError($q))
    	die($q->getMessage());
    $sql = 'UPDATE game_'.$_SESSION['game_id']." SET states = '$opposing_countries' WHERE id = $opposing_id";
    $q = $DB->query($sql);
    if(DB::isError($q))
    	die($q->getMessage());
    
    header("Location: ../game.php?display=status&tostate={$to_country_selectnum}&fromstate={$from_country_selectnum}&armies={$attacking_armies}&$gets");
}
else {
    header("Location: ../game.php?display=status&error=Phantom%20armies%20bug%20occured%20.%20Some%20recent%20information%20may%20have%20been%20lost.&$gets");
	exit;	
}
*/
?>
