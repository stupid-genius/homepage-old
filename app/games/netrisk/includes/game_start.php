<?php
require_once('_db.config.php');
// save rate GET variable
$matches = array();
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';

if(!$_SESSION['game_host']){ // only host can start game
   header("Location: ../game.php?error=Only%20the%20Host%20may%20start%20the%20game.&$gets");
   exit;
}
	
// make sure game isnt already started
$sql = 'SELECT state FROM games WHERE id = '.$_SESSION['game_id'];	
$gstate = get_one($sql);
if($gstate != 'Waiting'){ // game is already started
	header("Location: ../game.php?error=The%20game%20is%20already%20started.&$gets");
	exit;
}	
	
// check number of players so host cant start with only one player 
$sql = 'SELECT cards FROM game_'.$_SESSION['game_id']; // only need the world data cards and the num of rows for numplayers
$players = single_qry($sql);
$numplayers = (mysql_num_rows($players) - 1); // world_data is counted here so subtract 1

if($numplayers == 1){ // make sure theres at least 2 players
	header("Location: ../game.php?error=Not%20enough%20players%20to%20start%20the%20game,%20please%20wait%20for%20more%20players%20to%20join.&$gets");
	exit;
}

// Get the world data
$world_data = mysql_fetch_assoc($players);	
//$players->free(); // free query result

// Give players however many men they will place 
//exclude world_data of course
// Rules: 2 players = 40 armies apiece     3 players = 35    4=30     5=25     6=20     7=20    8=20   
// extend at end to make more interesting.. 
switch($numplayers){
	case 2:  $armies = 40; break;
	case 3:  $armies = 35; break;
	case 4:  $armies = 30; break;
	case 5:  $armies = 25; break;
	default:  $armies = 20; break; // for 6, 7, 8 and beyond...
}

// Deal out cards ( EXCLUDING WILD CARDS )
require_once('sql_arrays.php');
$cards = string_2_array($world_data['cards']);
//create an array of arrays to store all the players states
$players_states = array();
for ($i=1; $i<=$numplayers; $i++)
	$players_states[$i] = array();
// deal	
$playerid = 1;
foreach ($cards as $card){
	if($card == 44 or $card == 45)// skip wildcards
		continue;
	$players_states[$playerid][] = $card.'+1'; // add state to players state array and give the state one army
		// minus one so the array starts at 0 and not 1	
	($playerid == $numplayers) ? $playerid = 1 : $playerid++; // resets or increments playerid
}

// convert to DB form and Update DB		
$i = 2;
foreach($players_states as $p_states){ // convert to DB form of string
	$p_states = array_2_string($p_states);
	// Update player in game with given states and armies
	$sql = 'UPDATE game_'.$_SESSION['game_id']." SET state = 'initial', states = '$p_states', armies = $armies WHERE id = $i";
	$q = single_qry($sql);	
	$i++;
}
$sql = 'UPDATE games SET state = \'Initial\' WHERE id = '.$_SESSION['game_id']; // move game into start placement status
$q = single_qry($sql);

// Email players excluding Host and World_data notifying them that the game has started if they accept mail updates
//---------------------------------------------------

//---------------------------------------------------


// Clean UP SQL Table by deleting unused color values from world data id 0
$sql = 'UPDATE game_'.$_SESSION['game_id'].' SET color = \'\' WHERE id = 0';
$q = single_qry($sql);

header("Location: ../game.php?$gets");
?>
