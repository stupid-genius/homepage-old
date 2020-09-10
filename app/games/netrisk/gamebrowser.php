<?php
/*i18n pass*/
$i18n_gamebrowser = array();
$i18n_gamebrowser['en_US'] = array (
	'Create Game'=>'Create Game',
	'Warning: '=>'Warning: ',
	'Game Name'=>'Game Name',
	'#'=>'#',
	'Status'=>'Status',
	'Current Player'=>'Current Player',
	'Last Move'=>'Last Move',
	'for Players'=>'for Players',
	'Previous'=>'Previous',
	'Next'=>'Next'
);

require_once('includes/i18n.php');
global $i18n;
$i18n = $i18n_gamebrowser;

require_once('includes/_db.config.php');

if (isset($_SESSION['player_name'])){
  echo "<a class='button' href='index.php?page=creategame'>Create Game</a>";
}

if (isset($_GET['sort']))  { $sort = $_GET['sort']; } else { $sort = 'time'; }
if (isset($_GET['order']))  { $order = $_GET['order']; } else { $order = 'DESC'; }

$startnum = 0;
$sql = "SELECT * FROM games ORDER BY `$sort` $order";

$some_games = single_qry($sql);
$query_rows = mysql_num_rows($some_games);

if(isset($_GET['error'])){ 
  echo "<div class='error' id='error' style='top: 15px;'>";
  echo i18n("Warning: ").$_GET['error'];
  echo "<div class='close'><a href='javascript:OffError()'>X</a></div>";
  echo "</div>";
 }
?>
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
    	<th><!--kibitz--></th>
		<th><!--locked--></th>
<?php
switch($order) {
	case 'DESC': $order = 'ASC';
	break;
	case 'ASC': $order = 'DESC';
	break;
}
?>
<th>
<?php echo "<a href='index.php?page=gamebrowser&sort=name&order=".$order."'>";?>
<?php echo i18n("Game Name");?>
</a></th>
		<!--MW:again, comment out for now.<th>Type</th>-->
		<th>
		<?php echo i18n("#");?>
		</th>
		<th>
		<?php echo "<a href='index.php?page=gamebrowser&sort=state&order=".$order."'>";?>
		<?php echo i18n("Status");?>
		</a></th>
		<th>
		<?php echo i18n("Current Player");?>
		</th>
		<th>
		<?php echo "<a href='index.php?page=gamebrowser&sort=time&order=".$order."'>";?>
		<?php echo i18n("Last Move");?>
		</a></th>
	        </tr>

<?
$rownum = 0;
//while (($game = mysql_fetch_array($some_games))&& ($rownum < 20)){
while ($game = mysql_fetch_array($some_games)){
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
		}  // check row%2 to color rows
		?>" style="background-color:#<?php  if ($rownum%2) { echo "000"; } else { echo "1e1e1e";} ?>;">
		<td><?
		if($game['kibitz']) 
			echo '<a href="index.php?page=kibitz&id='.$game['id'].'"><img src="images/kibitz.gif" height="12" width="16" alt="Kibitz" /></a>'; 
		else ;
			/*echo '&nbsp;';*/
			
		?></td>
		<td><?
		if($game['password']){ echo '<img src="images/lock.gif" alt="Password" />'; $pass = 1; 
			} else { /*echo '&nbsp;';*/ $pass = 0; }
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
						     echo i18n('for Players');
						     break;
						   case "Finished":
						     echo '';
						     break;
						   case "Initial":
						     echo 'Placement';
						     break;
						   case "Playing":

			/* Find out who's the current player.*/
			$sql = 'SELECT player FROM game_'.$game['id'].' WHERE state != \'inactive\' AND state != \'initial\' AND state != \'dead\' AND state != \'waiting\' AND id != 1';
			$current_player = get_array($sql);
			if ($current_player[0] == $_SESSION['player_name']) {
				echo "<span style='color:red;'>".$current_player[0]."</span>";
			} else {
			echo $current_player[0];
				break;
		}}		
		?></td>
		
		
		<td><? 	echo substr($game['time'], 0, 10);	?></td>
		</tr><?
			echo "\n";
			$rownum++;
			
		
} ?>
</table>

<span class="rightalign">
<? 	// BROWSE TABLE SIZE different in IE and Mozilla (bigger in Mozilla so the the current max fits Mozilla but leaves space in IE )
	if (($startnum - 20) > -1){ ?>
		<a href="index.php?num=<?= $startnum - 20 ?>" style="color:#FFEECC">
				    <img src="images/arrow_left.gif" width="19" height="12" border="0" alt="Previous"><?php echo i18n("Previous");?></a> 
<?  }
	if($query_rows >= 20){ // theres another row for another page so show the Next Link. 
?>
	  <a href="index.php?num=<?= $startnum + 20 ?>" style="color:#FFEECC"><?php echo i18n("Next");?>
		<img src="images/arrow_right.gif" width="19" height="12" border="0" alt="Next"></a>
<? } ?> 
</span>


