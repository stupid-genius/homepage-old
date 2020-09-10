<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>NetRisk - Join Game</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="css/css_template.css" rel="stylesheet" type="text/css" />
	<link href="css/gamebrowser.css" rel="stylesheet" type="text/css" />
	<link href="css/base.css" rel="stylesheet" type="text/css" />
	<script language="javascript">
	<!--
	function OffJoin(){
		 window.location = "index.php?page=gamebrowser";
	}
	function OffError(){
		document.getElementById('error').style.display = 'none';
	}
	//-->
	</script>
	</head>
	<body>
	
	<? if(isset($_GET['error'])){ ?>
		<div class="error" id="error" style="left: 250px; top: 150px;">
		Warning: &nbsp; <?= $_GET['error'] ?>
		<div class="close"><a href="javascript:OffError()">X</a></div>
		</div>
	<? } ?>

	<div class="joinoptions" id="joinoptions">
		<span class="cancel"><a href="javascript:OffJoin()">Cancel</a></span>
		<div style="margin-top:-8px; margin-bottom: -2px; float:left;">
		<? if($playing){ ?>		
			The selected game has already started.
		<? } else if($full){ ?>
			The selected game is full.
		<? } else { ?>	
			<span id="joinform">
			You have not joined this game.
			<form action="joingame.php" name="joinform">
				<div>
				<? if($game_pass){ ?>
					<span id="joinpassword">
						<img src="images/lock.gif">
						<input type="text" name="pass" size="18" />
					</span> 
				<? } ?><br /> <br />
				<input value="Join" type="submit" />
				<input type="hidden" name="id" id="id" value="<?= $id ?>" />
				<input type="hidden" name="action" value="join" />
			</form></div>
			</span>
		<? } ?>
	</div>
	</body></html>

