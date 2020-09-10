<form action="includes/func_addarms.php" method="post" name="statusaction" id="addarmy">
<br />
<? // Get the number of armies the player has available to place
$sql = 'SELECT armies FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
$numarmies = get_one($sql);
?>
<input  class="button" type="hidden" name="numarmies" value="<?= $numarmies ?>">
<input class="button" type="hidden" name="gamestate" value="<?= $gamestate ?>">
You have <?= $numarmies ?> armies to place.
<br /><br />
Armies: <input class="button" name="armies" type="text" size="1"> 
<select  class="button" name="fromstate">
<option selected value="invalid">Select a Country:</option>
<? // add as options only the states the current player controls .. THESE SETTINGS COULD BE  GENEREATED EARLIER
while($a_state = current($_SESSION['STATES'])){ 
	if($a_state['player'] == $_SESSION['player_id']){ ?>
		<option value="<?= key($_SESSION['STATES']) ?>"><?= $a_state['name'] ?></option>	
<? 	} 
	next($_SESSION['STATES']);
} reset($_SESSION['STATES']); ?>
</select>
<br /><br/><hr>
<input  class="button" type="submit" value="Place Armies">
</form>