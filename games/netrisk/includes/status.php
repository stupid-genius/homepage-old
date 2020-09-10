<div id="status_area"><div style="text-align:center;font-weight:bold;">[ Status Area ]</div>
<?php 

if($gamestate == 'Waiting for Start'){// make sure only host can do this, check if host on session
		if($_SESSION['game_host'] == 1){ ?>
			<fieldset>
			<form action="includes/game_start.php" method="post">
			<input class="button" value="Start Game" type="submit" />				
			</form></fieldset><fieldset>
			<form action="includes/game_delete.php" method="post">
			<input class="button" value="Delete Game" type="submit" />
			</form></fieldset>
			
	<? 	} else { ?>
			Waiting on the host to start the game.				
	<? 	} 
	 } else if($gamestate == 'Placing Armies' || $gamestate == 'Initial Placement' || $gamestate == 'Forced Placement'){ 
		 require_once('includes/form_place.php');
	 } else if($gamestate == 'Waiting for Players'){ 
		require_once('includes/form_wait.php');	 	
	 } else if($gamestate == 'Attacking'){
 		if(!isset($_SESSION['conquer_data'])) // SESSION variable for conquer data		
			require_once('includes/form_attack.php');
		else
			require_once('includes/form_occupy.php');
	 } else if($gamestate == 'Fortifying'){
	 	require_once('includes/form_fortify.php');
	 } else if($gamestate == 'Trading Cards' || $gamestate == 'Forced Trading Cards'){
		
		require_once('includes/form_trade.php');
	 } else if($gamestate == 'Game Over'){
	 	$sql = 'SELECT player FROM game_'.$_SESSION['game_id'].' WHERE state = \'winner\' LIMIT 1';
	 	$winner = get_one($sql);		
	 	?><br /><br /> The game is over, <?= $winner ?> has won the game. <br />
		<?      if ($_SESSION['player_name'] == $winner){
			?><br /><fieldset>
			<form action="includes/game_delete.php" method="post">
			<input value="Delete Game" type="submit" />
			</form></fieldset><br />
		<?} else {
		echo '<br />';
		}
			
	 } else if($gamestate == 'Defeated'){ ?>
	 	<br /><br /> You have been defeated.		
<?   } else echo '<br /><br />Unknown game state.'; ?>
</div>
