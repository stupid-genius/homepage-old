<?php
require_once('_db.config.php');

if (isset($_GET['title'])) {
$_SESSION['chatid'] = $_GET['id'];
$_SESSION['chattitle'] = $_GET['title'];
$_SESSION['chatadmin'] = $_GET['admin'];
}
$chattitle = $_SESSION['chattitle'];
?>

<div id="inbox">
<span class="msg_ttl"><?php echo $chattitle; ?></span> <br />
	<iframe class="newstxt" style="background-color:#fff" src="includes/mass_chat.php" name="chat" height="235" width="620" >
    </iframe>
	<br /></div>

<div id="sendmsg" >
	<form action="includes/mass_action.php" name="message" method="post">
	 Post Message<br /><textarea name="msgarea" cols="55" rows="2" id="chat" class="newstxt"></textarea>
 	<br /><table><td>
	<input type="submit" value="Send Message" class="button"></form>
	</td>
<?php
// GIVES admin of chat room option to delete it.
if ($_SESSION['player_name'] == $_SESSION['chatadmin']) {
	?>
<form action="includes/mass_delete.php" method="post"><td style="width:100%;text-align:right;"> <input type="submit" value="Delete Chat Room" class="button"/></form>
	</td>
<?php  }  ?>
	</table>
	
</div>
