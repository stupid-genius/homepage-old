<?php
require_once('includes/_db.config.php');
require_once('includes/functions.php');
if (!isset($_SESSION['player_name'])){
	echo "You must login to view chats.";
} else {
?>
<a class="button" href="index.php?page=includes/mass_create">Create Thread</a> 
<br/>
<?php
if(isset($_GET['num'])){
	$startnum = $_GET['num'];
	$endnum = $startnum + 20;
	$sql = "SELECT * FROM chat ORDER BY `date` DESC LIMIT $startnum , $endnum ";	
} else { // set Limit to +1 to see if there is another pages worth of games
	$startnum = 0;
	$sql = 'SELECT * FROM chat ORDER BY `date` DESC LIMIT 0 , 20';
}	
$result = mysql_query($sql);

//ERROR Messaging
if(isset($_GET['error'])){ 
?>
	<div class="error" id="error" style="left: 440px; top: 15px;">
	Warning: <?= $_GET['error'] ?>
	<div class="close"><a href="javascript:OffError()">X</a></div>
	</div>
<? } 
// debug
//$bs = var_dump(mysql_fetch_assoc($result));
echo $bs;
//printf($bs);
?>


<table id="mass_browser" border="0">
	<!--JD game browser adjusted for chat. -->
	<colgroup class="mass_name" />	
	<colgroup class="mass_admin" />
	<colgroup class="mass_date" />
    <tr>
		<th>Thread Title</th>
		<th>Creator</th>
		<th>Last Post</th>
	</tr>

<?
$rownum = 0;
while ($chat = mysql_fetch_array($result)) {
	?><tr class="<?php if ($rownum%2) {echo "line_2"; }?>"><td><?
		echo '<input type="hidden" name="id" value="'.$chat['id'].'" /><input type="hidden" name="forumtitle" value="'.$chat['title'].'" /> <a href="index.php?page=includes/mass_display&amp;id='.$chat['id'].'&amp;title='.rawurlencode($chat['title']).'&amp;admin='.$chat['admin'].'">'.$chat['title'];
	?></a></td><td><? 	
		echo $chat['admin'];
	?></td><td><?
		echo $chat['date'];
	?></td></tr><?
		echo "\n";
		$rownum++;
} ?>
</table>
<!--MW: there are NO styles applied to this yet!-->
<span class="navs">
<? 	// BROWSE TABLE SIZE different in IE and Mozilla (bigger in Mozilla so the the current max fits Mozilla but leaves space in IE )
	if (($startnum - 20) > -1){ ?>
		<a href="index.php?num=<?= $startnum - 20 ?>" style="color:#FFEECC">
		<img src="images/arrow_left.gif" width="19" height="12" border="0" alt="Previous"> Previous</a> 
<?  }
	if($query_rows == 20){ // theres another row for another page so show the Next Link 
?>
		<a href="index.php?num=<?= $startnum + 20 ?>" style="color:#FFEECC">Next 
		<img src="images/arrow_right.gif" width="19" height="12" border="0" alt="Next"></a>
<? } ?> 
</span>

<?php } ?>
