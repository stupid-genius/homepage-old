<form action="func_trade.php" method="post" name="statusaction" id="addarmy">
<br />
<? // MAKE THE CARDS CLICK SELECTABLE!!!!!!!!!!!!!!!!
// Redirect if Force Trading requirements met 

require_once('includes/functions.php');

$sql = 'SELECT cards FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
$player_cards = get_one($sql);

if($player_cards){ // if the player has any cards show them	?>
	<table cellpadding="0" cellspacing="0" border="0"><tr>
	<?
	$player_cards = string_2_array($player_cards);
	$numcards = count($player_cards);
	
	$states_idkeyed = array();
	$checkboxvalues = array();	
	foreach ($player_cards as $pcard){
		$sql = "SELECT * FROM states WHERE id = $pcard";
		$state_info = get_assoc($sql);
		/*$state_info = $DB->query($sql);
		if(DB::isError($state_info))
			die($state_info->getMessage());
		
		$state_info = $state_info->fetchRow(DB_FETCHMODE_ASSOC);
		if (DB::isError($anotherplayer))
			die($anotherplayer->getMessage()); */
	
		$cardtype = $state_info['card_type'];
		switch($cardtype){ // display the card type
			case 1: $image = '<img src="images/infantry1_'.$_SESSION['player_color'].'.gif" width="55" height="38" alt="NetRisk Card"><br />';
					break;
			case 2: $image = '<img src="images/cavalry_'.$_SESSION['player_color'].'.gif" width="55" height="38" alt="NetRisk Card"><br />';
					break;
			case 3:	$image = '<img src="images/cannon_'.$_SESSION['player_color'].'.gif" width="55" height="38" alt="NetRisk Card"><br />';
					break;	
			case 0: $image = '<br /><br />';
					break;
			default: $image = 'unknown card type<br />'; break;
		}
		// MAX number of cards a person can have at one time SHOULD BE 9
		$checkboxvalues[] = $pcard.','.$cardtype; // for use by checkboxes below cards
		$states_idkeyed[$pcard] = $state_info['name']; // for use by dropdown menu below
		?>
		<td width="74" height="85">
		<span class="card">
		<?= $image ?><?= str_replace(' ','<br />',$state_info['name']) // should make long words on two lines ?>
		<br /></span>
		</td>
<? } ?>
</tr>
<?
if($numcards > 2){ // do this only if they actually have a chance of trading in cards, keeps from query overhead otherwise
	?><tr>&nbsp;
	</tr></table>
	<input type="hidden" name="cards" value="<?= array_2_string($player_cards) ?>">	
	<br />
	<?
	$player_states = array();		
	while($a_state = current($_SESSION['STATES'])){ 
		if($a_state['player'] == $_SESSION['player_id']){
			$player_states[] = key($_SESSION['STATES']);
		}
		next($_SESSION['STATES']);
	} reset($_SESSION['STATES']);
	// display the states on the bonuses that are controlled by the player
//list the countries controled by current player.
/*	while ($statename_idkeyed = current($states_idkeyed)){
		if(in_array(key($states_idkeyed), $player_states))
			echo 'Countries You Control:<br /><option value="'.key($states_idkeyed).'">'.$statename_idkeyed.'</option><br />';
		next($states_idkeyed);
	}	*/
	echo '&nbsp;';
} else echo '</table>';
?>
</form>
<? } else echo '<p>You have no cards.</p>'; ?>

<p><? if($numcards < 5){ // allow them to move on if they dont have five cards ?>

<? } else { ?>
You have 5 or more cards and must trade in at least one matching pair.
<? } ?></p> <?echo('Waiting for players to finish their turn.'); ?>
