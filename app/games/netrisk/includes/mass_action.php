<?php
require_once('_db.config.php'); // loads Pear DB and database connection string

$id = $_SESSION['chatid'];
$username = $_SESSION['player_name'];
$title = $_POST['chatroom'];
$postmsg = @strip_tags($_POST['msgarea'], '<b><i><span><br><strong>');

// Styling for the Mass Chat
$datestyle = "<span class='msg_date'>(".date("m/d H:i").")</span> ";
$newmsg = $datestyle."<strong>".$username."</strong> - ".$postmsg."<br />";

// query old messages
$sql = "SELECT messages FROM chat WHERE id=".$id;
$oldmsg = get_array($sql);
// combine the strings
$totalmsg = $newmsg.$oldmsg[0];

$sql = "UPDATE chat SET messages=\"$totalmsg\", date=NOW() WHERE id=".$id;
$result = single_qry($sql);

header("Location: ../index.php?page=includes/mass_display&id=$id");
?>
