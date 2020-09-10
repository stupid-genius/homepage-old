<?php
require_once('_db.config.php');
?>
<head>
<title>NetRisk - New Game Creation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function validate(){
	if (document.getElementById('createform').style.display != 'none'){
		if (document.forms[0].name.value==''){
			alert("Please fill out the Name field before submitting.");
			document.forms[0].name.focus();
		} else {
			document.getElementById('createform').style.display = 'none';
			document.getElementById('joingame').style.display = 'block';
		}
	} else if (document.getElementById('joingame').style.display != 'none'){
		<?php require_once('join_validate.js') ?>
	}
}
function previous(){
	document.getElementById('joingame').style.display = 'none';
	document.getElementById('createform').style.display = 'block';
}
</script>
</head>
<form id="newgame" action="includes/create.php" method="post">
<div class="createform" id="createform">

<span class="title">New Game Settings</span>
<br style="clear:both" />
<p>
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
Password <span class="note">- (16 char max)<br />
Leave this field blank if you wish to allow anyone to join</span><br />
<input name="password" type="text" size="16" maxlength="16" />
</p>
<div>

Notify Players <span class="note">- An email will be sent to the addresses below informing them to join this game</span><br />
<span class="leftalign"><input name="player1" type="text" size="40" />
<input name="player2" type="text" size="40" />
<input name="player3" type="text" size="40" />
<input name="player4" type="text" size="40" />
<input name="player5" type="text" size="40" />
<input name="player6" type="text" size="40" />
<input name="player7" type="text" size="40" />
<input name="player8" type="text" size="40" /></span>
<span class="note">* Will only notify the # of player's you selected above.</span>

<br /> <br />
<input name="kibitz" type="checkbox" value="1" checked/>Allow Kibitzing? <span class="note">- allows non-players to view the game from the browser.</span>
<br /><br /><br/>Player Time Limit
<select name="timelimit">
<option value="0">none</option>
<option value="43200">12 hrs</option>
<option value="86400">1 day</option>
<option value="172800">2 days</option>
<option value="259200">3 days</option>
<option value="432000">5 days</option>
<option value="604800">1 week</option></select>
<br /><br/><br/>
<div class="rightalign"><a class="button" style="font-size:.8em;" href="javascript:validate()">Next >></a></div></div>
</div>
<!--  end create.html -->
<div id="joingame" style="display:none">
<!-- begin of join.html -->
<div class="joinform">
<span class="title">Player Configuration</span><br /><br />
<p class="rightalign">Color<span class="note"> - the color of your units<br /><br /></span>
<label><!-- have to do a color check on page generation and submittal for non host players so no two people are the same color 
			and make sure that one of the radio buttons is selected at the beginning-->
  <input type="radio" name="color" value="purple" checked />
  <img src="images/infantry1_purple.gif" alt="infantry" />
  <img src="images/cavalry_purple.gif" alt="cavalry" />
  <img src="images/cannon_purple.gif" alt="cannon" /></label>
  <br />
  <label>
  <input type="radio" name="color" value="red" />
  <img src="images/infantry1_red.gif" alt="infantry" />
  <img src="images/cavalry_red.gif" alt="cavalry" />
  <img src="images/cannon_red.gif" alt="cannon" />
  </label>
  <br />
  <input type="radio" name="color" value="blue" />
  <img src="images/infantry1_blue.gif" alt="infantry" />
  <img src="images/cavalry_blue.gif" alt="cavalry" />
  <img src="images/cannon_blue.gif" alt="cannon" /></label>
  <br />
  <input type="radio" name="color" value="green" />
  <img src="images/infantry1_green.gif" alt="infantry" />
  <img src="images/cavalry_green.gif" alt="cavalry" />
  <img src="images/cannon_green.gif" alt="cannon" /></label>
  <br />
  <input type="radio" name="color" value="black" />
  <img src="images/infantry1_black.gif" alt="infantry" />
  <img src="images/cavalry_black.gif" alt="cavalry" />
  <img src="images/cannon_black.gif" alt="cannon" /></label>
  <br />
  <input type="radio" name="color" value="teal" />
  <img src="images/infantry1_teal.gif" alt="infantry" />
  <img src="images/cavalry_teal.gif" alt="cavalry" />
  <img src="images/cannon_teal.gif" alt="cannon" /></label>
  <br />
  <input type="radio" name="color" value="yellow" />
  <img src="images/infantry1_yellow.gif" alt="infantry" />
  <img src="images/cavalry_yellow.gif" alt="cavalry" />
  <img src="images/cannon_yellow.gif" alt="cannon"/>
  </label>
  <br />
  <input type="radio" name="color" value="grey" />
  <img src="images/infantry1_grey.gif" alt="infantry" />
  <img src="images/cavalry_grey.gif" alt="cavalry" />
  <img src="images/cannon_grey.gif" alt="cannon" /></label>
  <br />
</p>
<p>Select the color you would like for your pieces.
<input name="player" type="hidden" size="30" maxlength="32" value="<?= $_SESSION['player_name'] ?>"/></p>
<p>
<input name="email" type="hidden" size="30" /></p>
<p><br />
<input name="playerpassword1" type="hidden" size="16" maxlength="16" value="<?= $_SESSION['player_pass']?>"/></p>
<p><br />
<input name="playerpassword2" type="hidden" size="16" maxlength="16" value="<?= $_SESSION['player_pass']?>"/></p>
<a class="button" style="font-size:.8em;" href="javascript:previous()"><< Previous</a>
<a class="button" style="font-size:.8em;" href="javascript:validate()">Create Game >></a>
</div>
<!-- end of join.html -->
</div>

<div class="creating" id="creating" style="display:none">
<span class="loadtitle"><br />Creating Game ... Please Wait</span>
</div>

</form>
