<?php
require_once('includes/_db.config.php');
require_once('includes/functions.php');
// GET game id and displays game
if(!isset($_GET['id'])){ // id not set
	index_error_header("You must select a legitimate game to kibitz.");
	exit;
}

$game_id = $_GET['id'];
$game_name = $_GET['gamename'];
$player_id = 0;
// check if the given game allows kibitzing
$sql = "SELECT kibitz FROM games WHERE id = $game_id";
$kibitz = get_one($sql);
if(!$kibitz){
	index_error_header("The selected game does not allow kibitzing.");
	exit;
}

$_SESSION['game_id'] = $game_id; // register game_id to session so game_data loads correctly
require_once('includes/game_data.php'); // loads data necessary to render screen and options
// query games database for game state (playing or waiting)
$sql = "SELECT state FROM games WHERE id = $game_id";
$gamestate = get_one($sql);


switch($gamestate){  
	case 'Waiting':	$gamestate = 'Waiting for Start';
			break;
	case 'Finished': $gamestate = 'Game Over';
			break;
	default: $gamestate = 'Playing';
			check_Initial();
			break;
}

// Check if game is in Initial Placement stage so should only show one piece per country

?>

<head>
<link href="css/game.css" rel="stylesheet" type="text/css" />
<link href="css/board.css" rel="stylesheet" type="text/css" />
<link href="css/base.css" rel="stylesheet" type="text/css" />
</head>
<? if ($gamestate == 'Waiting for Start'){ ?>
		<div class="map">
			<img src="images/worldmap.jpg" border="0" height="568" width="828">
		</div> 
<? } else {
		include('includes/kibitz_map.php');
	} ?>

