<?php
require_once("_db.config.php");
// Loads game data necessary to render screen and options 
// into associative arrays stored in the SESSION.
$STATES = array();
$PLAYERS = array();
$temp_player_states = array(); // used for splitting and storing before putting into STATES

// Retrieve all the game data from the Database.
// ID#0 - World_Data is not relevant for general functions.

//If the session is expired, redirect to index
if(!isset($_SESSION['game_id'])){
     header("Location: index.php");
}
$sql = 'SELECT id, player, host, state, color, cards, states, email FROM game_'.$_SESSION['game_id'].' WHERE id > 1';
$q = mysql_query($sql);

// Loop through each row of the game data, each of which contains
// all the information about the games players.
// Store the necessary pieces of data
while ($player = mysql_fetch_assoc($q)){
	// Only storing multiple used values -- so no host, messages, state
		$PLAYERS[$player['id']] = array('name' => $player['player'],
					'state' => $player['state'],
					'color' => $player['color'],
					'email' => $player['email'],
          'host' => $player['host'],);
		$temp_player_states[$player['id']] = $player['states'];
}
$players_data = 0;

// Now save each states name into the STATES array -- Do name first to sort by name
$sql = "SELECT id, name FROM countries WHERE id < 44 ORDER BY name ASC"; // 44 being the first wildcard
$q = mysql_query($sql);
/*$states_data = $DB->query($sql);
if(DB::isError($states_data))
	die($states_data->getMessage());*/

	while ($a_state = mysql_fetch_assoc($q)){

	$STATES[$a_state['id']] = array('name' => $a_state['name']); // save each state name into STATES		
}

// Assumes every state is owned by some player
$tlen = count($temp_player_states);
for($j=0; $j<$tlen; $j++){ // loop through each players states
	if($tmp_states_data = current($temp_player_states)){ // if player doesnt have states dont do anything
		$tmp_states_storage = explode(',', $tmp_states_data); // breakup the states WITH their data
		$slen = count($tmp_states_storage);
		for($i=0; $i<$slen; $i++){ // loop through each state and its data
			$tmp_states_storage[$i] = explode('+', $tmp_states_storage[$i]); // break up the states FROM their data
			// save armies & owner player by id into STATES array
			$STATES[$tmp_states_storage[$i][0]]['armies'] = $tmp_states_storage[$i][1];
			$STATES[$tmp_states_storage[$i][0]]['player'] = key($temp_player_states);
		}		
	}
	next($temp_player_states);
} 
unset($temp_player_states);

// Save to sessions and unset the arrays used
$_SESSION['PLAYERS'] = $PLAYERS;
$_SESSION['STATES'] = $STATES;
$_SESSION['all_cards'] = count($player_data['cards']);
unset($PLAYERS);
unset($STATES); ?>
