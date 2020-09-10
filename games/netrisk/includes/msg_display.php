<?php
// for user to change refresh rate it just reloads the iframe to a url with a different GET rate passed
if (isset($_GET['rate'])) {
    $rate = $_GET['rate'];
    if($rate < 5) $rate = 5;
} else {
    $rate = 0;
}
?>
<script language="JavaScript">
<!--
function reloadchat() {
  if (document.getElementById('refresh').checked) {
    window.location.href = 'game.php?rate=' + document.getElementById('rate').value;
  } else {
    window.location.href = 'game.php';
  }
}
function pausechatrefresh() {
  window.frames['chat'].location.href = 'includes/msg_chat.php';
}
function resumechatrefresh() { // added bonuse of refreshing chat every time user click on inbox!
  window.frames['chat'].location.href = 'includes/msg_chat.php<? if ($rate) echo '?rate='.$rate; ?>';
}
-->
</script>

<div id="sendmsg"><span class="msg_ttl">[ Messages ]</span>
	<form action="includes/msg_action.php" name="message" method="post">
	<!-- a href="javascript:inboxtab(); resumechatrefresh();">* View Messages</a><br / -->
	<textarea name="msgarea" cols="12" rows="5"></textarea>
	<br /><input type="submit" value="Post"/>
	 to <select name="recipient[]" size="3" multiple="multiple" >
	<option value="0">All Players</option>
	<? while ($oneplayer = current($_SESSION['PLAYERS'])){
			if(key($_SESSION['PLAYERS']) != $_SESSION['player_id']){
				echo '<option value="'.key($_SESSION['PLAYERS']).'">'.$oneplayer['name']."</option>\r\n";
			}
			next($_SESSION['PLAYERS']);
		}// reset($_SESSION['PLAYERS']);   <--  moved to sendmessage.php
		?>
	</select>
	</form>
</div>

<div id="inbox">
	<!--strong><a href="javascript:sendmsgtab(); pausechatrefresh();">* Compose Message</a></strong>
<br / -->
	<iframe style="background-color:#fff" id="chat" name="chat" src="_test.php<? if ($rate) echo '?rate='.$rate; ?>" height="190" width="130">
    </iframe>
	<br /><!-- br />
	Refresh Chat? <input type="checkbox" id="refresh" <? if ($rate) echo 'checked' ?> /> &nbsp;
    every <input id="rate" type="number" maxlength="2" value="<?= $rate ?>"/> seconds (min 5s).
    <input type="button" onclick="reloadchat();" value="Apply" / -->
</div>

