<?php
$conquer_data = $_SESSION['conquer_data'];
// get the data from the Session variable
$conquered_state_id = $conquer_data[0];
$available_armies = $conquer_data[1];
//$player_states = $conquer_data[2];
$min_occupy = $conquer_data[2];
$fromstate = $conquer_data[3];
// get the conquered state name
$conquered_state = $_SESSION['STATES'][$conquered_state_id]['name'];

function create_number_list($min,$max){
	$html = '';
	for ($i = $min; $i <= $max; $i++){
	$html .= "<option value=\"{$i}\">{$i}</option>\n";
	}
	return $html;
}

?>
You have conquered <?= $conquered_state ?>!<br />
Available armies: <?= $available_armies ?><br />
Must Transfer: <?= $min_occupy ?><br>
<!-- Please enter how many of these armies you wish to occupy <?= $conquered_state ?> with.-->
<br />
<form action="includes/func_occupy.php" method="post" name="statusaction" id="occupyarmy">
	Armies: <select name="armies"><?php echo create_number_list($min_occupy, $available_armies); ?></select>
	<input type="hidden" name="fromstate" value="<?= $fromstate ?>" >	
	<input type="hidden" name="state_id" value="<?= $conquered_state_id ?>">	
	<input type="hidden" name="max_occupy" value="<?= $available_armies ?>">	
	<input type="hidden" name="min_occupy" value="<?= $min_occupy ?>">
	<input type="submit" value="Occupy" class="button">
</form>

<form action="includes/func_occupy.php" method="post" name="statusaction" id="occupyarmy"> 
	<input type="hidden" name="armies" value="<?= $available_armies ?>">
	<input type="hidden" name="fromstate" value="<?= $fromstate ?>" >
	<input type="hidden" name="state_id" value="<?= $conquered_state_id ?>">
	<input type="hidden" name="max_occupy" value="<?= $available_armies ?>">
	<input type="hidden" name="min_occupy" value="<?= $min_occupy ?>">
<hr> <br />
	<input type="submit" value="Move All (<?= $available_armies ?>)" class="button">
</form>


