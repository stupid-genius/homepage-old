<?php
// DB connection
require_once('includes/_db.config.php');
require_once('includes/functions.php');

// GET info from POST
$reply = $_POST['reply'];
$user = $_POST['user'];
$user_id = $_SESSION['player_id'];
$title = $_POST['title'];
$message = $_POST['message'];
$topicid = $_POST['topicid'];
$timestamp = time();

if ($message == FALSE) {
header("Location: index.php?page=forum&error=Missing_Info");	
exit;
}

//if it is a reply, change the thread number
if (isset($reply)) { 
	$thread = $reply; 
} else { 
	$thread = $topicid;
}
// if it is not a reply, make it a parent Post
$level = '2';
if (isset($reply)) { $level = '3';}
if (isset($title)) { $level = '1'; }

$title = strip_tags($title);
$message = strip_tags($message);

$sql = "INSERT INTO forum (thread, level, user, user_id, title, message, timestamp) VALUES('$thread', '$level', '$user', '$user_id', '$title', '$message', '$timestamp')";
$q = mysql_query($sql);

$last_id = $thread;

$sql = "UPDATE forum SET timestamp = '$timestamp' WHERE id = '$last_id' && level = '1' LIMIT 1";
$q = mysql_query($sql);

// get the topic id;
//$sql = "SELECT id FROM";

if (isset($topicid)) { 
header("Location: index.php?page=forum&topicid=$topicid"); 
} else {
header("Location: index.php?page=forum");
}
?>
