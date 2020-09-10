<!--MW: changed ampersands to entities-->
<?php
require_once('includes/_db.config.php');

if(isset($_GET['num'])){
	$startnum = $_GET['num'];
	$endnum = $startnum + 20;
//	$sqlchat = "SELECT * FROM forum ORDER BY `date` DESC LIMIT $startnum , $endnum ";	
	$sql = "SELECT * FROM games ORDER BY `time` DESC LIMIT $startnum , $endnum ";
	
} else { // set Limit to +1 to see if there is another pages worth of games
	$startnum = 0;
	$sql = 'SELECT * FROM games ORDER BY `time` DESC LIMIT 0 , 20';
//	$sqlchat = 'SELECT * FROM chat ORDER BY `date` DESC LIMIT 0 , 20';
}	

$some_games = single_qry($sql);
$query_rows = mysql_num_rows($some_games);
//$result = single_qry($sqlchat);


?>
 
<span class="navs">
<? 	// BROWSE TABLE SIZE different in IE and Mozilla (bigger in Mozilla so the the current max fits Mozilla but leaves space in IE )
	if (($startnum - 20) > -1){ ?>
		<a href="index.php?page=admin/gamelist&num=<?= $startnum - 20 ?>" style="color:#FFEECC">
		<img src="images/arrow_left.gif" width="19" height="12" border="0" alt="Previous"> Previous</a> 
<?  }
	if($query_rows >= 20){ // theres another row for another page so show the Next Link. 
?>
		<a href="index.php?page=admin/gamelist&num=<?= $startnum + 20 ?>" style="color:#FFEECC">Next 
		<img src="images/arrow_right.gif" width="19" height="12" border="0" alt="Next"></a>  ( Applies to both tables )
<? } ?>
</span>
<h3>Games</h3>
<table id="game_browser">
	<!--MW:classes in case we want styles on columns (width)-->
	<colgroup class="kibitz" />
	<colgroup class="locked" />
	<colgroup class="game_name" />
	<colgroup class="game_players" />
	<colgroup class="game_status" />
	<colgroup class="current_player" />
	<colgroup class="game_time" />	
    <tr>
    	<th><!--del--></th>
		<th><!--kibitz--></th>
		<th>Game Name</th>
		<!--MW:again, comment out for now.<th>Type</th>-->
		<th>#</th>
		<th>Status</th>
		<th>Current Player</th>
		<th>Last Move</th>
	</tr>

<?
$rownum = 0;
while (($game = mysql_fetch_array($some_games))&& ($rownum < 20)){
		?>
		
		<tr class="<?
		switch ($game['state']) {
			case "Waiting":
				echo 'browsewait';
				break;
			case "Playing":
				echo 'browseplay';
				break;
			case "Finished":
				echo 'browsefin';
				break;
		}
		?>"  style="background-color:#<?php  if ($rownum%2) { echo "000"; } else { echo "1e1e1e";} ?>;">
		<td><a style="color:red;font-weight:800;" href="admin/edit.php?mod=delgame&game=<?= $game['id']; ?>">X</a>
		</td>
		<td><?
// Kibitz
		if($game['kibitz']) 
			echo '<a href="kibitz.php?id='.$game['id'].'&amp;gamename='.rawurlencode($game['name'])
				.'"><img src="images/kibitz.gif" 
height="12" width="16" alt="Kibitz" /></a>'; 
		else ;
			/*echo '&nbsp;';*/
			
		?></td>
		<td><?
		($game['players'] == $game['capacity']) ? $full = 1 : $full = 0;
		($game['state'] != 'Waiting') ? $playing = 1 : $playing = 0;
		echo '<input type="hidden" name="'.$game['id'].'" value="'.$game['id'].'" /> <a href="login.php?id='.$game['id'].'&amp;gamename='.rawurlencode($game['name']).'">'.@stripslashes($game['name']);
		?></a></td>
		
		<td><? 	echo $game['players'].'/'.$game['capacity']; ?></td>
		<td><? 	echo $game['state']; ?></td>
		
		<td><?
		switch ($game['state']) {
			case "Waiting":
				echo '';
				break;
			case "Finished":
				echo '';
				break;
			case "Playing":

			/*JD:060401: fixed all that shit w/ a basic query*/
			$sql = 'SELECT player FROM game_'.$game['id'].' WHERE state != \'inactive\' AND state != \'initial\' AND state != \'dead\' AND state != \'waiting\' AND id != 1';
			$current_player = get_array($sql);

			echo $current_player[0];
				break;
		}		
		?></td>
		
		
		<td><? 	echo substr($game['time'], 0, 10);	?></td>
		</tr><?
			echo "\n";
			$rownum++;
		
} ?>
</table>


