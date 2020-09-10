<form action="includes/func_trade.php" method="post" name="statusaction" id="addarmy">
You may trade cards when you have set of three.<br /> <table id="cards">
<?
// Redirect if Force Trading requirements met 

require_once('functions.php');

$sql = 'SELECT cards FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
$player_cards = get_one($sql);

 // if the player has any cards show them
if($player_cards){	

	$player_cards = string_2_array($player_cards);
	$numcards = count($player_cards);
	
	$states_idkeyed = array();
	$checkboxvalues = array();	
	foreach ($player_cards as $pcard){
		$sql = "SELECT * FROM countries WHERE id = $pcard";
		$state_info = get_assoc($sql);
	
		$cardtype = $state_info['card_type'];
		switch($cardtype){ // display the card type
			case 1: $image = '<img src="images/infantry1_'.$_SESSION['player_color'].'.gif" width="55" height="38" alt="NetRisk Card">';
					break;
			case 2: $image = '<img src="images/cavalry_'.$_SESSION['player_color'].'.gif" width="55" height="38" alt="NetRisk Card">';
					break;
			case 3:	$image = '<img src="images/cannon_'.$_SESSION['player_color'].'.gif" width="55" height="38" alt="NetRisk Card">';
					break;	
			case 0: $image = '<br /><br />';
					break;
			default: $image = 'unknown card type<br />'; break;
		}
		// MAX number of cards a person can have at one time SHOULD BE 9
		$checkboxvalues[] = $pcard.','.$cardtype; // for use by checkboxes below cards
		$states_idkeyed[$pcard] = $state_info['name']; // for use by dropdown menu below
		
		$i = 0;
		?>
		<?php
		if($numcards > 2){
		
			while (list($key, $value) = each($checkboxvalues)) {
		?><tr><td>
		<input name="cardandtype[]" type="checkbox" value="<?= $value ?>">
		<input type="hidden" name="cards" value="<?= array_2_string($player_cards) ?>">	
		</td>
		<td class="cards_bg"><?= $image ?></td><td><?= $state_info['name']; ?><br /></td></tr>	
		<?php  $i++;
			}  
		} else {  	?>
			<tr><td class="cards_bg"><?= $image ?></td><td><?= $state_info['name'] ; ?><br /></td></tr>
			<?php
	} }
?>
</table>

<?php  } else { echo '</table><p>You have no cards.</p>'; }?><br/>
  <!-- If you control the country of a card you trade, you will get two units in that country.  But for only one of the cards. --> 
<? if($numcards < 5){ // allow them to move on if they dont have five cards 
 } else { ?>
You have 5 or more cards.<!-- and must trade in at least one matching pair.-->
<? } ?>
<?php
// New area....
if($numcards > 2){
?>
	<input type="hidden" name="cards" value="<?= array_2_string($player_cards) ?>">	
	<br /><input type="submit" value="Turn In Cards">
		<select name="bonus_state"><option value="invalid"> +2 Bonus State</option>
	<?
	$player_states = array();		
	while($a_state = current($_SESSION['STATES'])){ 
		if($a_state['player'] == $_SESSION['player_id']){
			$player_states[] = key($_SESSION['STATES']);
		}
		next($_SESSION['STATES']);
	} reset($_SESSION['STATES']);
	// display the states on the bonuses that are controlled by the player
	while ($statename_idkeyed = current($states_idkeyed)){
		if(in_array(key($states_idkeyed), $player_states))
			echo '<option value="'.key($states_idkeyed).'">'.$statename_idkeyed.'</option>';
		next($states_idkeyed);
	}	
	echo '</select>';
}?></form>
<hr>
next action...<br /><br/>
<a href="includes/nextstatus.php" class="button">Placement >> </a>

<?php
/*echo "<pre>";
print_r($checkboxvalues);
echo "</pre>"; */
?>
