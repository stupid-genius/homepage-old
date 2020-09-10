<?php
require_once('includes/_db.config.php');

// emails should be sent to host each time a player joins his game

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>NetRisk - New Game Creation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/css_template.css" rel="stylesheet" type="text/css" />

</head>
<body>
<form id="newgame" action="includes/create.php" method="post">

<div class="createform" id="createform">
<!-- it use to include create.html here, but im trying to combine it -->

<div id="joingame">
<span class="title"><img src="images/logo_menu.gif" width="150" height="30" alt="NetRisk" style="float:left" />New Game Settings</span>
<br style="clear:both" />
<p class="leftalign">
Game Name <span class="note">- the name used to identify your game to other players (32 char max)</span><br />
<input name="name" type="text" size="30" maxlength="32" />
</p>
<p class="rightalign">
<select name="players">
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select>
# of Players <span class="note"><br />the number of human players </span><br />
</p>
<p>
Password <span class="note">- allows users to join this game and configure their player settings (16 char max)<br />
Leave this field blank if you wish to allow anyone to join</span><br />
<input name="password" type="text" size="16" maxlength="16" />
</p>
<div>

Notify Players <span class="note">- An email will be sent to the players emails below informing them of how to join this game<br />
<span class="leftalign"><input name="player1" type="text" size="40" />
<input name="player2" type="text" size="40" />
<input name="player3" type="text" size="40" />
<input name="player4" type="text" size="40" />
<input name="player5" type="text" size="40" />
<input name="player6" type="text" size="40" />
<input name="player7" type="text" size="40" />
<input name="player8" type="text" size="40" /></span>
<br />
<span class="padding">*If you notify more players than the selected # of players then only the first of that number 
you selected who join will be able to play in this game</span></span>
</div>
<div class="clearfloat">
<input name="kibitz" type="checkbox" value="1" />Allow Kibitzing?
</div>
<div class="rightbutton"><input value="Create" type="submit" /></div>
 
</div>
</div>

</form>
</body>
</html>
