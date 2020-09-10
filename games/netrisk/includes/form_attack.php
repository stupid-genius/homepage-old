<form action="includes/func_attack.php" method="post" name="statusaction" id="attackarmy">
<br />
<?
if (isset($_GET['tostate']))
	$toselected = $_GET['tostate'];
else
	$toselected = 0;
if (isset($_GET['fromstate']))
	$fromselected = $_GET['fromstate'];
else
	$fromselected = 0;
?>
Armies: <select name="armies" size="1" class="button">
<option value="1">1</option>
<option value="2">2</option>
<option value="3" selected>3</option>
</select>
<br/>
<select name="fromstate" class="button">
<option <? if(!$fromselected) echo 'selected'; ?> value="invalid">Attack From:</option>
<? // add as options only the states the current player controls
$selectnum = 1;	
while($a_state = current($_SESSION['STATES'])){ 
	if($a_state['player'] == $_SESSION['player_id']){ ?>
		<option value="<?= key($_SESSION['STATES']).','.$selectnum ?>" <? 
			// ^ provide the stated id and the selectnumber as value for easy multiple attacks		
		if($fromselected == $selectnum) echo 'selected'; 
		?>><?= $a_state['name'] ?></option><?
		$selectnum++;
	}
	next($_SESSION['STATES']);
} reset($_SESSION['STATES']); ?>
</select><br />
<select name="tostate" class="button">
<option <? if(!$toselected) echo 'selected'; ?> value="invalid">Attack To:</option>
<? // add as options states controlled by all other players
$selectnum = 1;	
while($a_state = current($_SESSION['STATES'])){ 
	if($a_state['player'] != $_SESSION['player_id']){ ?>
		<option value="<?= key($_SESSION['STATES']).','.$selectnum ?>" <? 
		if($toselected == $selectnum) echo 'selected'; 
		?>><?= $a_state['name'] ?></option><?
	}
	next($_SESSION['STATES']); $selectnum++;
} reset($_SESSION['STATES']); ?>
</select><br /><br />
<input type="submit" value="Launch Attack" class="button">
</form>
<hr>
next action...<br><br><br><br><br><br><br>
<a href="includes/nextstatus.php" class="button_red">Fortify >> </a>
