<?php
require_once('includes/_db.config.php');
//$pid = $_SESSION['player_id'];

if(!isset($_GET['pid'])) {
  $sql = "SELECT * FROM users WHERE login='".$_SESSION['player_name']."'";
  $userinfo = get_assoc($sql);
  $pid = $userinfo['id'];
}
else {
  $pid = $_GET['pid'];

$sql = "SELECT * FROM users WHERE id=".$pid;
$userinfo = get_assoc($sql);
}
?>
<div class="status_area" style="width:auto;margin: 10px 35px 0 35px;">
<table cellpadding="6"><td valign="top"><br />
<img src="player_avatar.php?image_id=<?php echo $pid;?>">
</td><td valign="top">
<h3> <?= $userinfo['login'];?></h3>

   <table><tr>
   <td style="text-align:right;">
   <?php echo $userinfo['lose']; ?>
   </td>
   <td> Games Played </td>
   </tr><tr>
   <td style="text-align:right;">
   <?php echo $userinfo['win']; ?>
   </td>
   <td> Players Defeated </td> 
   </tr><tr>
   <td style="text-align:right;">
    <?php // this formats it into the 'batting average' style
	echo number_format((($userinfo['win'] / $userinfo['lose'])/10),3); ?>
    </td>
   <td> Record (average number of players defeated per game)</td>
   </tr></table>

   
<br>
<small>Bio:</small> <?php echo $userinfo['bio']; ?> <br>
</td></table>
<?php
if ($_SESSION['player_name'] == $userinfo['login'] && $_SESSION['player_name'] != "") {
	echo "<a href=\"index.php?page=includes/player_update\">edit ?</a>";
}
?>
</div>
