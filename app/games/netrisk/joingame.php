<?php
require_once('includes/_db.config.php');
require_once('includes/functions.php');

$id = $_GET['id'];
$error = $_GET['error'];
if ($error) { header("Location: index.php?page=gamebrowser&error=$error&$gets");}
/*if(!isset($id)){ // must have id be set to be given options
	index_error_header("You must login to play a game. (joingame)");
	exit;
}*/	

// Get game information
$sql = "SELECT password, players, capacity, state FROM games WHERE id = $id"; 
$game = get_assoc($sql);

// Check he current conditions of the Game.
$game_pass = $game['password'];	// game password
$game_state = $game['state']; // state of the game
($game_state != 'Waiting') ? $playing = true : $playing = false; // check if game is started
($game['players'] == $game['capacity']) ? $full = true : $full = false; // check if game is full
$user_pass = (isset($_GET['pass'])) ? $_GET['pass'] : $_POST['pass']; // user password either by GET or POST

/* If user is not in the game, give them a chance to join (if possible).*/
// Prompt user for password if not given and one is required, or allow to login or join if action isnt set
if(((!isset($user_pass) || !$user_pass) && $game_pass) || !isset($_GET['action']) || $full || $playing)
{
	//Give Users the error that allows them to join the game
	header("Location: index.php?page=includes/notplayer&id=$id");
	exit;
}

$action = $_GET['action']; // action value doenst matter what it is as long as it is set or not

// if password compare user pass with DB pass
if($game_pass){ // check if game has a pass
	if($game_pass != md5($user_pass)){
		joingame_error_header("Incorrect password, please try again.");
		exit;
	}		
};

/* Handle the player's colors */
// List available colors to Choose from.
// shouldnt ever be called form a context where there is no $id initialized... put a isset check?
		$sql = "SELECT color FROM game_$id WHERE id = 1";
		$q = mysql_query($sql) or die('Could not quey color information: '.mysql_error());
		if ( $row = mysql_fetch_array($q)) $sql_data = $row[0];
			$color_array = string_2_array($sql_data);

?>

	<script type="text/javascript">
	function validate(){	
	<?php require_once('includes/join_validate.js') ?>	
	}
	</script>
	<div id="joingame">		
	<form id="newplayer" action="includes/join_post.php" method="post">
<!-- form_join -->
	<div class="joinform">
	<span class="title">Player Configuration</span><br /><br />

<p style="float:right;">Color<span class="note"> - the color of your units<br /><br /></span>
	<?php 
	$default = 'checked'; // used to select as default the first available color
			foreach ($color_array as $availabe_color){?>
				<label>
  				<input type="radio" name="color" value="<?php echo "{$availabe_color}\" $default" ?>" />
  				<img src="images/infantry1_<?php echo $availabe_color ?>.gif" alt="infantry" />
  				<img src="images/cavalry_<?php echo $availabe_color ?>.gif" alt="cavalry" />
  				<img src="images/cannon_<?php echo $availabe_color ?>.gif" alt="cannon"/></label>
  				<br />
		<? $default = '';	}  ?> 
			</p>
			<p>Hello &nbsp;<? echo $_SESSION['player_name']?>,
			<input name="player" type="hidden" size="30" maxlength="32" value="<? echo $_SESSION['player_name']?>"/></p>
			<p>Would you like to recieve emails on your turn?<br />
			<input type="checkbox" value="yes" name="mailupdates"/> - Yes !
			<input name="email" type="hidden" /></p>
			<p>
			<input name="id" type="hidden" value="<? echo $id ?>" />
			<br />
			<div><input class="button" type="submit" value="Join Game" /></div>
			</div>

		<input name="id" type="hidden" value="<?php echo $id ?>" />
	</form></div>
	<div class="creating" id="creating" style="display:none">
	<span class="loadtitle">Creating Game ... Please Wait</span>
	</div>

