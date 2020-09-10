<?php
require_once('includes/_db.config.php');
require_once('includes/functions.php');
$game_id = $_GET['id'];
//MDW: unregister globals pass 061028
$_SESSION['game_id'] = $game_id;
 
// Get game information
$sql = "SELECT password, players, capacity, state FROM games WHERE id = ".$_SESSION['game_id']; 
$game = get_assoc($sql);
?>

You are not a player in this game. 
<br />
<?php 

if ($game['players'] >= $game['capacity']) {
	echo "Game is currently Full. <br />";
} else if ($game['state'] != "Waiting") {
	echo "Game has already begun. <br />";
} else {
?>
Would you like to Join? 
<?php if (!$game['password']) { ?>
<a class="button" href="index.php?page=joingame&id=<?php echo $_SESSION['game_id'];?>&action=join">Yes</a>
<?php
}
	if($game['password']){ ?>
			<form action="index.php?page=joingame&id=<?php echo $_SESSION['game_id']; ?>&action=join" method="post" >
			<span id="joinpassword">Game is locked.  Please type password:  
				<img src="images/lock.gif">
				<input type="text" name="pass" size="18" />
				<input type="submit" value="Yes" />
			</span> </form>
	<? } ?>
<p><p>
<?php
}
	$sql = "SELECT timelimit FROM games WHERE id =".$_SESSION['game_id'];
	$q = get_one($sql);
	if ($q > 0 ) { 
		?>This Game's Timelimit is <?php echo seconds_to_HMS($q);
	} else {
		?>This Game has no Timelimit.<?php 
	}
 require_once('color_bar.php'); ?>

