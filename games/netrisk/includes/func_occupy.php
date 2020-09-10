<?php
//MDW 04 FEB 2006: fixed 'spontaneous infinite loop'.
//made some variable names more sane. (overloaded state)
require_once('_db.config.php');
require_once('functions.php');

$session_copy = $_SESSION;

// save rate GET variable
$matches = array();
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';

if(!isset($_SESSION['conquer_data'])){ // no longer occupy stage
    game_error_header("You are not in an Occupation state.");
	exit;
}

$country_id = $_POST['state_id']; // conquered state id
$from_country = $_POST['fromstate'];
$max_occupy = $_POST['max_occupy']; // max number of armies player can station in conquered territory
$min_occupy = $_POST['min_occupy']; // min number of armies player can station in conquered territory
$armies = $_POST['armies'];

if($armies > $max_occupy){
    game_error_header("You do not have that many armies to occupy with");
   exit;
} else if($armies < $min_occupy){
   game_error_header("You must occupy with more armies.");
   exit;
}

// remove from conqueror
if(($_SESSION['STATES'][$from_country]['armies'] - $armies) >=1){
$_SESSION['STATES'][$from_country]['armies'] -= $armies;
	}else{
		game_error_header("Majic's Bugfix!");
		unset($_SESSION['conquer_data']);
		exit;
	}
// add to conquered
$_SESSION['STATES'][$country_id]['armies'] += $armies;
// reform the states for DB
$new_countries = array();
$countries = $_SESSION['STATES'];
while($country = current($countries)){ 
	if($country['player'] == $_SESSION['player_id']){
		$new_countries[] = key($countries).'+'.$country['armies'];
	}
	next($countries);
} unset($countries);
$new_countries = array_2_string($new_countries);

// update DB with new state and its armies
if ( update_countries($DB, $_SESSION['game_id'], $_SESSION['player_id'], $new_countries) ){
    // Remove conquer data from the Session
    unset($_SESSION['conquer_data']);
    header("Location: ../game.php?display=status&$gets");    
} else {
    //restore session to original state and return countries error.
    $_SESSION = $session_copy;
    countries_error();
}
/*$sql = 'UPDATE game_'.$_SESSION['game_id']." SET states = '$new_countries' WHERE id = ".$_SESSION['player_id'];
$q = $DB->query($sql);
if(DB::isError($q))
	die($q->getMessage());
*/
?>
