<?php
require_once('_db.config.php');

if (!isset($_SESSION['player_name'])) { 
	echo " "; 
} else {

	$games = 0;
	$sql = "SELECT id FROM games WHERE id > 0";
	$games = get_col($sql);

	$id = 0;
?>
	<div id="top_ten" class="sidebar" style="position:relative; left: -4px;">
	<h2>My Games</h2>
	</div>
	<div id="top_ten" class="sidebar" style="position:relative; left: -4px;overflow: auto; height: 210px; width: 220px;">
	<table>
	<tr><th>#</th>
	<th>Game Names</th></tr>
	<table id="mygames">
<?php

	$rownum = 0; // Fix the green bar effect
	while ($id < count($games)) {
		//$sql = "SELECT name FROM games WHERE id = ".$games[$id][0]." AND ( SELECT player FROM game_".$game[$id][0]." player = 'jon')";
		$sql = "SELECT player, state FROM game_".$games[$id][0]." WHERE player = '".$_SESSION['player_name']."'";
		$player = get_array($sql);
	 
		if ($player[0] == $_SESSION['player_name']) {
	
			$sql = "SELECT name FROM games WHERE id = ".$games[$id][0];
			$gamename = get_one($sql);
?>
			<tr class="<?php if ($rownum%2) { echo "line_2"; } ?>"> <!--// New to fix green bar-->
 		 	 <td><?php echo $games[$id][0]; ?></td>
 		 	 <td class="state-<?php echo $player[1]; ?>">
  		  	  <a href="login.php?id=<?php echo $games[$id][0]; ?>&amp;gamename=<?php rawurlencode($gamename);?>"><?php echo $gamename; ?></a>
 		 	 </td>
			</tr>
<?php 
			$rownum++;
		}
		$id++ ;
	}
?>
	</table></div>
<?php
}
?>

