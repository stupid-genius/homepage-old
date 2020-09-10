<?php


if($_GET['debug'])
   require_once('includes/debug_funcs.php');

require_once('includes/_db.config.php');
require_once('includes/game_data.php'); // loads data necessary to render screen and options
require_once('includes/functions.php');

// defines what is in window
$control_win = $_GET[display];
if ($_GET[display]==""){
	$control_win="status";
}

// Moved the Game state * player state functions to functions.php
$timeout = player_time_limit();
$gamestate = game_state();
$playerstate = player_state();

require_once('includes/game_data.php'); // loads data necessary to render screen and options

///////////  USE GET TO POINT TO A CERTAIN STARTING DIV TO SHOW
$display = $_GET['display']; // should be made to work with submenus also
switch ($display){
	case 'status': $msgdiv = 'block';
		$statusdiv = 'block'; $optdiv = 'none';
		break;
	case 'msg': $statusdiv = 'block';
		$optdiv = 'none'; $msgdiv = 'block';
		break;
	case 'opt': $optdiv = 'block';
		$msgdiv = 'none';  $statusdiv = 'none';
		break;
	default: $msgdiv = 'block';  // status div is default
		$statusdiv = 'block'; $optdiv = 'none';
		break;
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>NetRisk - <?= $_SESSION['game_name'] ?> - <?= $gamestate ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/game.css" rel="stylesheet" type="text/css" />
<link href="css/board.css" rel="stylesheet" type="text/css" />
<link href="css/base.css" rel="stylesheet" type="text/css" />
<?php

require_once('includes/riskconfig.php');
include('includes/wordspew.php');

jal_add_to_head();

?>
<script type="text/javascript" src="includes/BubbleTooltips.js"></script>
<script type="text/javascript">
window.onload=function(){enableTooltips("color_bar")};
</script>
<script language="JavaScript">
<!--
<? include('includes/details_controls.js'); ?>
	function OffError(){                  
		document.getElementById('error').style.display = 'none';
	}

<?php
if($_GET['debug']) { ?>
        function exitpop(myThing)
        {
        my_window= window.open ("",
          "mywindow1","status=1,width=350,height=150,scrollbars=1,resizable=1");

           my_window.document.write('<table border=1 cellspacing=0>');
           my_window.document.write('<tr><th>Key</th><th>Value</th></tr>');
        for (i in myThing) {
            my_window.document.write('<tr><td>' + i + '</td><td>' + myThing[i] + '</td></tr>');
        }
        }
<?php } 
?>

// -->
</script>
<?php if($_GET['debug']) { ?>
<script type="text/javascript" language="javascript" src="includes/PHP_Unserialize.js"></script> 
<?php } ?>
</head>
<body>

<? if(isset($_GET['error'])){ ?>
	<div class="error" id="error" style="left: 235px; top: 250px;">
	Warning: &nbsp; <?= $_GET['error'] ?>
	<div class="close"><a href="javascript:OffError()">X</a></div>
	</div>
<?php } ?>


<div id="game_header">
    <div id="game_column-in">
    <p><div id="logo" style="top: -5px;">
                <img src="images/logo.gif" alt="Welcome to Netrisk!" />
                <span id="version"><?= $version ?></span></div>
<div id="login" style="height:1.4em;width: auto;font-size: .8em; position:relative;top: -5px; background-color:#192614; border: 2px solid black; padding: 3px; ">
<strong style="color:#FF6B51">Game</strong> <?= $_SESSION['game_id'] ?>: <strong><?= $_SESSION['game_name'] ?></strong>

<?php 
// Get timelimit info for the player
$timeout = seconds_to_HMS((time() - $_SESSION['game_timestamp']));
	if ($_SESSION['game_timelimit'] ==0) { $limit = "<strong style='color:#FF6B51'>no timelimit</strong>"; }
	else { $limit = "<strong style='color:#FF6B51'>timelimit: </strong>".seconds_to_HMS($_SESSION['game_timelimit']);  
		echo " - ".$limit." - "; 
		echo "<strong style='color:#FF6B51'>lastmove: </strong>".$timeout," ago. "; 
	}
	?>
<a class="button" href="logout.php">Logout</a><br/>

<br>
</div>
<div id="nav"> 
                        <a href="game.php?display=status">Status</a>
                        <a href="game.php?display=options">Options</a>
                        <a href="index.php?page=gamebrowser">Browser</a>
      	</div>
</p>
    </div>
</div>

    <div id="game_container">
        <div id="game_board">
            <? if ($gamestate == 'Waiting for Start'){ ?>
               <div class="map">
			        <img src="images/worldmap.jpg" border="0" height="568" width="828">
                </div>
            <? } else {
		require('includes/dice.php');
                include('includes/new_game_map.php');
            } ?>
 
        </div>
        <div id="game_controls">
            <div class="game_column-in">                   
                        <?php require_once('includes/'.$control_win.'.php'); ?>
			<br />
			<?php require_once('includes/log_display.php');?>
            </div>
        </div>
        <div id="game_info">
            <div class="game_column-in">
                	<?php require('includes/color_bar.php');
 ?>
			<div class="status_area"><?php jal_get_shoutbox(); ?></div>
            </div>
        </div>
    </div>
    <div id="game_footer">
    <?php
    		include('online.php');
// Debugging code.
    if($_GET['debug']) {

       $_DEBUGNETRISK['SESSION'] = multi_array_to_string($_SESSION);
       $_DEBUGNETRISK['GET'] = multi_array_to_string($_GET);
       $_DEBUGNETRISK['POST'] = multi_array_to_string($_POST);


    $var = array();
    foreach ($_DEBUGNETRISK as $k => $v) {
            $var[$k] = $v;
    }

    $serialized = EscapeString(serialize($var));

    ?>
    <script language="javascript" type="text/javascript">
    <!--
        toUnserialize = '<?=$serialized?>';

        myThing = PHP_Unserialize(toUnserialize);
    // -->
    </script> 
<a onClick="exitpop(myThing);">Debug Output</a>
<?php
 } ?>
    </div>
    <!-- cross browser javascript for tooltips http://www.walterzorn.com/tooltip/tooltip_e.htm -->
    <?php /*MW: check into this later, maybe an easier way*/ ?>
    <script language="JavaScript" type="text/javascript" src="includes/wz_tooltip.js"></script>
</body>
</html>
