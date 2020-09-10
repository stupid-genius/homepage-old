<div id="color_bar" class="status_area" style="margin-left:15px;">
<?php

require_once('_db.config.php');
require_once('includes/game_data.php');
require_once('functions.php');// loads data necessary to render screen and options
// query games database for game state (playing or waiting)

$game_id = $_SESSION['game_id'];// register game_id to session so game_data loads correctly
$sql = "SELECT state FROM games WHERE id =".$game_id;
$q = mysql_query($sql) or die('Game Data has not been created yet.');

switch($gamestate){  
	case 'Waiting':	$gamestate = 'Waiting for Start';
			break;
	case 'Finished': $gamestate = 'Game Over';
			break;
	default: $gamestate = 'Playing';
			check_Initial();
			break;
}

check_Initial();

// FInd next card trade bonus
$sql = 'SELECT diplomacy FROM game_'.$_SESSION['game_id'].' WHERE id = 1'; // get the card bonus numbers
	$card_bonuses = get_one($sql);
	$card_bonuses = string_2_array($card_bonuses);
	$army_bonus = $card_bonuses[0]; 
?>
<strong style='color:#FF6B51'>next Trade</strong>: <?= $army_bonus ?> <br/>

<table cellpadding="3" cellspacing="0" border="0">
		<tr style="background-color:#000000;"><td style="background-color:#192614"></td><td style=" color: #fff;">c</td><td width="100">Player</td><!--td>State</td--></tr>
		<?php
			function lightgrey($color){ // because IE doesnt know what grey is
				if($color == 'grey')
					return 'lightgrey';
						else
					return $color;
				}

		$bgcolor = '#EEEEEE';
		$txtcolor = '#000';
		foreach($_SESSION['PLAYERS'] as $aplayer){
			//find out cards
		$sql = "SELECT cards FROM game_".$_SESSION['game_id']." WHERE player = '".$aplayer['name']."'";
                $image_sql = "SELECT id, image_type, image_name FROM users WHERE login='".$aplayer['name']."'";
                $image_result = single_qry($image_sql);
                $image_row = mysql_fetch_array($image_result);

		if ($q = get_one($sql)){
		$tmp = string_2_array($q);
                if($_GET['debug'])
                    $_DEBUGNETRISK[$aplayer['name']] = multi_array_to_string($tmp);
                    $cards = count($tmp);
		} else { $cards = 0; }

			echo '<tr style="color:#222222; background-color:'.$bgcolor.';"><td style="background-color:#192614">';

			if ($aplayer['state'] == 'dead') {
				echo '<img src="images/dead.gif">';
			} else if ($aplayer['state'] == 'initial' && $aplayer['host'] == 0 && $_SESSION['game_host'] == 1) {
        echo "<a href='includes/host_kick.php?player=".$aplayer['name']."'> kill </a>";
      } else if ($aplayer['state'] != 'inactive') {
				echo '<img src="images/arrow.gif">';	
			} else {
				echo '&nbsp';	
			}
      echo '</td>';
			
			$txtcolor = "black";
			if ($aplayer['color'] == "black") { $txtcolor = "white"; }
                       
              echo '<td style="color:'.$txtcolor.';background-color:'.lightgrey($aplayer['color']).'">';
                        echo $cards;
                        echo '</td><td>';
			$title = $aplayer['name'];
			$href = $aplayer['state'];

			if($image_row['image_type'] == "") {
            echo "<img style=\"float: left\" src=\"images/blank.gif\"><a href='$href' title='$title' style='color:black'>".$aplayer['name']."</a>";
      } else {
	          echo "<a href='$href' title='$title'><img style=\"float: left\" src=\"player_avatar.php?image_id=".$image_row['id']."\"></a>";
      }
                   
            echo '</td></tr>';
			($bgcolor == '#EEEEEE') ? $bgcolor = '#AAAAAA' : $bgcolor = '#EEEEEE';
		}		
		//<td>'.get_player_state($aplayer['state']).'&nbsp; </td>
		

 ?></table>
</div><br />

