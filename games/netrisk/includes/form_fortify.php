<form action="includes/func_fortify.php" method="post" name="statusaction" id="fortifyarmy">
<br />
Armies: <input name="armies" type="text" size="1" class="button">
<br />
<select name="fromstate" class="button">
<option selected value="invalid">Fortify From:</option>
<?
$players_states = array(); // create for quicker use on second form - removes conditional checks
while($a_state = current($_SESSION['STATES'])){ 
	if($a_state['player'] == $_SESSION['player_id']){ ?>
		<option value="<?= key($_SESSION['STATES']) ?>">
		<?= $a_state['name'] ?></option><?
		$players_states[key($_SESSION['STATES'])] = $a_state['name'];
	}
	next($_SESSION['STATES']);
} reset($_SESSION['STATES']); 
?>
</select><br />
<select name="tostate" class="button">
<option selected value="invalid">Fortify To:</option>
<?
while($a_state = current($players_states)){ ?>
	<option value="<?= key($players_states) ?>"><?= $a_state ?></option><?
	next($players_states);
} reset($players_states); 
?>
</select><br /><br />
<input type="submit" value="Fortify" class="button">
</form>
<hr>
next action...<br /><br/>
<a href="includes/nextstatus.php" class="button">End Turn >> </a>
