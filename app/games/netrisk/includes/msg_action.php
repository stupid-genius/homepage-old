<?php
require_once('_db.config.php'); // loads Pear DB and database connection string

if(ini_get('magic_quotes_gpc')){ // if magic quotes is enabled dont add slashes
	$themessage = $_POST['msgarea'];
} else {
	$themessage = addslashes($_POST['msgarea']); 
}

$themessage = strip_tags($themessage); // need to strip HTML and php tags since
// it will be displayed direct to HTML, later maybe allow certain HTML...

$pcolor = ($_SESSION['player_color'] == 'grey') ? 'lightgrey' : $_SESSION['player_color'];
if ($_POST['recipient'][0] == 0){ // send message to everyone
	$sql = 'SELECT id FROM game_'.$_SESSION['game_id'].' WHERE id > 1';
	$playerids = get_array($sql);
	foreach($playerids as $id){ // now update each players message with this users message being sent
		$sql = 'SELECT messages FROM game_'.$_SESSION['game_id']." WHERE id = $id";
		$oldmsgs = get_one($sql);		
		$oldmsgs = addslashes($oldmsgs);
		
		// NEED TO STRIP HTML TAGS....  taken out: <span class=\"msg_date\">[ ".date("m/d H:i").' ]</span>
		$sql = 'UPDATE game_'.$_SESSION['game_id']." SET messages = '".
            '<span style=\"color:'.$pcolor.'\">'.$_SESSION['player_name'].'</span> : <br />'.
	    " {$themessage}<br /> {$id} <br/>{$oldmsgs}' WHERE id = $id";
		$q = single_qry($sql);	
	}
} else { // send only to those select discounting the 0 player if it is selected
	$id_array = $_POST['recipient'];
	$id_array[] = $_SESSION['player_id']; // also send to the sending player
	foreach ($id_array as $id){
		$sql = 'SELECT messages FROM game_'.$_SESSION['game_id']." WHERE id = $id";
		$oldmsgs = get_one($sql);			
		$oldmsgs = addslashes($oldmsgs);
        // trying to show the recipients of private messages name.	
        $msg_header = 'private';
		$sql = 'UPDATE game_'.$_SESSION['game_id']." SET messages = '".
            '<span style=\"color:'.$pcolor.'\">'.$_SESSION['player_name'].'</span> : '.
            "<span class=\"msg_private\">[{$msg_header}]</span><br />{$id} <br />{$themessage}<br />{$oldmsgs}' WHERE id = $id";
		$q = single_qry($sql);	
			
		reset($_SESSION['PLAYERS']);
	}		
}
// save rate GET variable
$matches = array();
preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
if (count($matches))
    $gets = $matches[0];
else $gets = '';

header("Location: ../game.php");
?>
