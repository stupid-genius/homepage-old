<?php
// DB connection
require_once('includes/_db.config.php');
require_once('includes/functions.php');
/*i18n pass*/
require_once('includes/i18n.php');
global $i18n;
$i18n = array(
	'en_US' => array (
		'could not query '=>'could not query ',
		' ago)'=>' ago)',
		'reply'=>'reply',
		' ago)'=>' ago)',
		'Previous'=>'Previous',
		'Next'=>'Next',
		'Topic'=>'Topic',
		'Poster'=>'Poster',
		'Since Last Post'=>'Since Last Post',
		'by:'=>'by:',
		'(reply)'=>'(reply)',
		'title:'=>'title:',
		'message:'=>'message:',
		'send'=>'send'
		)
);
/*end i18n pass*/

if (!isset($_SESSION['player_name'])){ 	
echo "You must login to view forum."; 
return;
}

if (isset($_GET['sort']))  { $sort = $_GET['sort']; } else { $sort = 'time'; }
if (isset($_GET['order']))  { $order = $_GET['order']; } else { $order = 'DESC'; }

$reply = $_GET['reply'];
$topicid = $_GET['topicid'];

// Show HTML
?>
	<html>
	<head>
	<style type="text/css">
	</style>
	<body>
		<div id="mass_window">
	<?php

if (isset($topicid)) {
// If the user is viewing a topic, show the first post.	
	$sql = "SELECT id, thread, level, user, user_id, title, message, timestamp FROM forum WHERE id = $topicid AND level = 1";
	$q = mysql_query($sql);
	while ($temp = mysql_fetch_array($q)) {
		list($id, $thread, $level, $user, $user_id, $title, $message, $timestamp) = $temp;
		echo "<div class='mass_title'>".$title."</div><div id='mass_level_1'><img src='player_avatar.php?image_id=".$user_id."'><span class='mass_user'>".$user."</span> <br/>".$message."</div>";	
	} 
	?>


	<?php
	// GET level 2 (sub) messages
	$sql = "SELECT id, thread, level, user, user_id, title, message, timestamp FROM forum WHERE thread = $topicid AND level = 2 ORDER BY timestamp ASC";
	$q = mysql_query($sql) or die(i18n('could not query ') .mysql_error());
	
	// Display sub messages
	while ( $row = mysql_fetch_array($q)) {

		list($id, $thread, $level, $user, $user_id, $title, $message, $timestamp) = $row;
	// Build the Style for the post
		echo "<div id='mass_level_2'><img src='player_avatar.php?image_id=".$user_id."'><span class='mass_user'>".$user." </span><span class='mass_date'>(".seconds_to_DHMS( time() - $timestamp).i18n(' ago)')."</span><a href='index.php?page=forum&topicid=$topicid&reply=$id'>".i18n('reply')."</a> <br />".$message."</div>";
	
	// GET level 3 post (child messages)
	$sql2 = "SELECT id, thread, level, user, user_id, title, message, timestamp FROM forum WHERE thread = ".$id." AND level = 3 ORDER BY timestamp ASC";
	$q2 = mysql_query($sql2);
	// Display level 3 post (Child Messages)
	while ($thread = mysql_fetch_array($q2)) {
		list($t_id, $t_thread, $t_level, $t_user, $t_user_id, $t_title, $t_message, $t_timestamp) = $thread;
		
		echo "<div id='mass_level_3'><img src='player_avatar.php?image_id=".$t_user_id."'><span class='mass_user'>".$t_user." </span><span class='mass_date'>(".seconds_to_DHMS(time() - $t_timestamp).i18n(' ago)')."</span> <br />".$t_message."</div> ";
	} 
	}
	?> 
<?php } else {
	// GET parent messages	
	if(isset($_GET['num'])){
	$startnum = $_GET['num'];
	$endnum = $startnum + 20;
	$sql = "SELECT id, user, title, message, timestamp FROM forum WHERE level = 1 ORDER BY timestamp DESC LIMIT $startnum , $endnum ";
		
} else { // set Limit to +1 to see if there is another pages worth of games
	$startnum = 0;
	$sql = "SELECT id, user, title, message, timestamp FROM forum WHERE level = 1 ORDER BY timestamp DESC LIMIT 0, 20";
	
}

	$q = mysql_query($sql) or die('could not query ' .mysql_error());

	if (($startnum - 20) > -1){ 
	  echo "<a href=\"index.php?page=forum&num=".($startnum - 20)."\" style=\"color:#111\">".i18n('Previous')."</a>";
	}
	if($query_rows >= 20){ // theres another row for another page so show the Next Link. 
	  echo "<a href=\"index.php?page=forum&num=".($startnum + 20)."\" style=\"color:#111\">".i18n('Next')."</a>";
	} 

	echo "<table id='forum_table'>
		<tr><td style='width:300px;'>".i18n('Topic')."</td><td>".i18n('Poster')."</td><td>".i18n('Since Last Post')."</td></tr>";	
	while ($row = mysql_fetch_array($q)) { 
		
		list($id, $user, $title, $message, $timestamp) = $row;
		echo "<tr><td class='forum_topic_front'> <a href='index.php?page=forum&topicid=$id'>".$title."</a></td><td> <span class='mass_date'>".i18n('by:')."</span>".$user." </td><td>".seconds_to_DHMS(time() - $timestamp)."</tr>";
		}
	echo "</table>";
	}?>
<!-- HTML for posting -->
<div id="forum_post_box">
	<form action="forum_post.php" method="post">
	<input type="hidden" value="<?= $_SESSION['player_name'] ?>" name="user">
	<input type="hidden" value="<?= $_SESSION['player_id'] ?>" name="user_id">
	<br />
<?php
if (isset($reply)) { 	
  echo "<input name='reply' type='hidden' value='$reply'>".i18n('(reply)'); 
	}
if (!isset($topicid)) {	
  echo i18n('title:')."<input type='text' name='title'><br />";
} else { echo "<input type='hidden' name='topicid' value='$topicid'>"; }
?>
<?php echo i18n('message:');?>
<br />
<textarea name="message"></textarea>
<br />
<input type="submit" <?php echo "value='".i18n("send")."'>";?>
</form> </div></div>
