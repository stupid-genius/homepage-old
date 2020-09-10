<?php
//MDW 060211: cleaned up again (phantom armies)
//mdw 04 feb 2006: fixed 'spontaneous infinite loop', 'state overloading',
//added error if player can't move that many armies (prevents 'i fortified to
//the wrong place' user error ;)
require_once('_db.config.php');
require_once('functions.php');

// make a backup of session info
$session_copy = $_SESSION;

// save rate GET variable
$matches = array();
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';


// make sure the player is fortifying state
$sql = 'SELECT state FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];	
$pstate = get_one($sql);

if($pstate != 'fortifying'){
	//header("Location: ../game.php?error=You%20are%20not%20in%20the%2Fortifying%20state.&$gets");
    game_error_header("You are not in the Fortifying state");
	exit;
}	


$from_country = $_POST['fromstate']; // max number of armies player can station in conquered territory
$to_country = $_POST['tostate']; // min number of armies player can station in conquered territory
$armies = $_POST['armies'];

if($to_country == 'invalid' || $from_country == 'invalid'){
    game_error_header("Invalid state selection");
	exit;
}
if($armies < 1){
   game_error_header("You must fortify with more armies");
   exit;
}
////// CHECK IF tostate AND fromstate ARE ADJACENT ///////////////////
$sql = "SELECT adjacencies FROM countries WHERE id = $from_country";
$country_adjacencies = get_one($sql);
$country_adjacencies = string_2_array($country_adjacencies);

if(!in_array($to_country, $country_adjacencies)){
    game_error_header("Your armies cannot reach the selected state");
	exit;
} ////////////////

//MDW 04 feb 2006: causing me grief, replacing with error
// choose how many armies to transfer, $from_country_armies - 1 is the max available
//$armies = min($_SESSION['STATES'][$from_country]['armies'] - 1, $armies);
// armies is the players choice but it may be wrong if it is greater than the max available
// if so this just transfers the max available
if ($armies > $_SESSION['STATES'][$from_country]['armies'] -1) {
    game_error_header("You do not have that many armies available.");
	//header("Location: ../game.php?display=status&error=You%20do%20not%20have%20that%20many%20armies%20available.&$gets");
	exit;
}

// move armies to tostate
$_SESSION['STATES'][$from_country]['armies'] -= $armies;
$_SESSION['STATES'][$to_country]['armies'] += $armies;

// create new value for DB
// MDW 04 FEB 2006: fixed infinite loop here.
$new_countries = array();
$countries = $_SESSION['STATES'];
while($country = current($countries)){ 
	if($country['player'] == $_SESSION['player_id']){
		$new_countries[] = key($countries).'+'.$country['armies'];
	}
	next($countries);
} unset($countries);
$new_countries = array_2_string($new_countries);

//MDW 060211
// changed to reusable function that dodges phantom armies
// function is in functions.php
if (update_countries($DB, $_SESSION['game_id'], $_SESSION['player_id'], $new_countries) ){
    //end turn
    header("Location: nextstatus.php?$gets");
} else {
    //restore session to original state
    $_SESSION = $session_copy;
    //do phantom armies error
    countries_error();
	exit;
}



/*
if (substr_count($new_countries,'+') > 0) {
    // update DB
    $sql = 'UPDATE game_'.$_SESSION['game_id']." SET states = '$new_countries' WHERE id = ".$_SESSION['player_id'];
    $q = $DB->query($sql);
    if(DB::isError($q))
    	die($q->getMessage());	
    	
    // end turn
    header("Location: nextstatus.php?$gets");
}
else {
    header("Location: ../game.php?display=status&error=Phantom%20armies%20bug%20occured%20.%20Some%20recent%20information%20may%20have%20been%20lost.&$gets");
	exit;	
}*/
?>
