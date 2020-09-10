<?php
//mdw 04 feb 2006: fixed 'spontaneous infinite loop', 'state overloading',
require_once('_db.config.php');
require_once('functions.php');

// save rate GET variable
$matches = array();
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';

// make sure the player is trading state
$sql = 'SELECT state FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];	
$player_status = get_one($sql);
if(!($player_status == 'trading' || $player_status == 'forcetrade')){
	game_error_header("You can not trade cards now.");
	//header("Location: ../game.php?error=You%20are%20not%20in%20a%20Trading%20state.&$gets");
	exit;
}	

require_once('functions.php');

$card_and_type = $_POST['cardandtype']; // an array of the checkbox values
$cards = string_2_array($_POST['cards']); // an array of the players cards
if(isset($_POST['bonus_state'])) // state to recieve bonus armies
	$bonus_country_id = $_POST['bonus_state'];

if(count($card_and_type) != 3){ // user did not select three cards
	game_error_header("You must select three cards.");
	//header("Location: ../game.php?display=status&error=You%20must%20select%20three%20cards.&$gets");
	exit;
}
// divide out the card and type array
$card_countries = array();
$card_types = array();
foreach($card_and_type as $ct){
	$ct = string_2_array($ct); // convert it to an array where 0 is the stateid and 1 is they type
	$card_countries[] = $ct[0];
	$card_types[] = $ct[1];
}
// Check that a valid 3 match is made
// Valid Matches
// 1 2 3 // in any order
// 0 x x	// ditto
// // 0 0 x this case doesnt matter, previous case will be met first 
if(in_array(0, $card_types) || 
		(in_array(1, $card_types) && in_array(2, $card_types) && in_array(3, $card_types)) || 
		($card_types[0] == $card_types[1] && $card_types[1] == $card_types[2])	){ // valid matches
	// Add armies
	// make sure to add when updating so as not to wipe out the normal turn given armies
	$sql = 'SELECT diplomacy FROM game_'.$_SESSION['game_id'].' WHERE id = 1'; // get the card bonus numbers
	$card_bonuses = get_one($sql);
	
	if(!$card_bonuses){ // in case there are no more card bonuses....
		header("Location: nextstatus.php"); // go onto next stage regardless....
		exit;	
	}
	$card_bonuses = string_2_array($card_bonuses);
	if(count($card_bonuses) == 1) {
		$army_bonus = $card_bonuses[0]; }
	else
	 	$army_bonus = array_shift($card_bonuses);
	$card_bonuses = array_2_string($card_bonuses);
	// Update player with bonus armies
	$sql = 'UPDATE game_'.$_SESSION['game_id']." SET armies = armies + $army_bonus WHERE id = ".$_SESSION['player_id']; // get the card bonus numbers
	$q = single_qry($sql);
	
    /*    // Message players about card turn-ins...
        $sql = 'SELECT id FROM game_'.$_SESSION['game_id'].' WHERE id > 1';
        $playerids = get_col($sql);
        foreach($playerids as $id){
	        $sql = 'SELECT messages FROM game_'.$_SESSION['game_id']." WHERE id = $id";
                $oldmsgs = get_one($sql);
                $oldmsgs = addslashes($oldmsgs);
                $sql = 'UPDATE game_'.$_SESSION['game_id']." SET messages = '".
                "<span class=\"msg_date\">[ ".date("m/d H:i").
                ' ]</span> <span style=\"color:'.$pcolor.'\">'.$_SESSION['player_name'].'</span> ~ <span class=\"msg_trade\">[trade]</span> '.
                'turned in cards for $army_bonus armies.<br />'.
                "{$oldmsgs}' WHERE id = $id";
                $q = single_qry($sql);
        }*/
	// Add two armies to the bonus state if it is selected
	if(isset($bonus_country_id) && $bonus_country_id != 'invalid' && in_array($bonus_country_id, $card_countries)){ // make sure its one of the handed in cards too
		// add bonus armies
		$_SESSION['STATES'][$bonus_country_id]['armies'] += 2;
		// create value for DB
		$player_countries = array();

        //MDW another infinite loop
        $countries = $_SESSION['STATES'];
		while($country = current($countries)){ 
			if($country['player'] == $_SESSION['player_id']){
				$player_countries[] = key($countries).'+'.$country['armies'];
			}
			next($countries);
		} reset($countries);
		$player_countries = array_2_string($player_countries);
		// update DB with new states
		$sql = 'UPDATE game_'.$_SESSION['game_id']." SET states = '$player_countries' WHERE id = ".$_SESSION['player_id'];
		$q = single_qry($sql);		
	}
	// Remove cards from player 
	$cards = array_2_string(array_diff($cards, $card_countries)); // removes card states used cards from the cards array
	$sql = 'UPDATE game_'.$_SESSION['game_id']." SET cards = '$cards' WHERE id = ".$_SESSION['player_id'];
	$q = single_qry($sql);
	
	// Add cards back to game card deck
	$sql = 'SELECT cards FROM game_'.$_SESSION['game_id'].' WHERE id = 1';	
	$game_card_deck = get_one($sql);
	$game_card_deck = string_2_array($game_card_deck); // to array
	$game_card_deck = array_merge($game_card_deck, $card_countries); // merge array w/ used cards, should add to end of deck
	$game_card_deck = array_2_string($game_card_deck); // to string
	$sql = 'UPDATE game_'.$_SESSION['game_id']." SET cards = '$game_card_deck' WHERE id = 1";
	$q = single_qry($sql);

	// Update bonus list
	$sql = 'UPDATE game_'.$_SESSION['game_id']." SET diplomacy = '$card_bonuses' WHERE id = 1"; // get the card bonus numbers
	$q = single_qry($sql);

} else {	 // No Match
	game_error_header("The cards you selected are not a valid set.");
	//header("Location: ../game.php?display=status&error=Cards%20do%20not%20form%20a%20valid%20match.&$gets");
	exit;	
}

// Leave player in this stage as long as they want, though the form forces them to trade if they have five
header("Location: ../game.php?display=status&$gets"); // stay in this state
?>
