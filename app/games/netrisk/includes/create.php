<?php
require_once('_db.config.php'); // loads Pear DB and database connection string
require_once('functions.php');

// GET GAME SETTINGS
$gamename = @strip_tags($_POST['name']);
$numplayers = $_POST['players']; 
$timelimit = $_POST['timelimit'];
$gamemode = 'individual';//$_POST['mode']; // placeholders for un-implemented game settings
$gametype = 'domination';//$_POST['type'];
$gamepass = $_POST['password']; // can correctly eval gamepass as true or false since '' is false
if($gamepass)	$gamepass = md5($gamepass); // encode it otherwise do nothing
$players = array($_POST['player1'], $_POST['player2'],$_POST['player3'],$_POST['player4'],
	$_POST['player5'],$_POST['player6'],$_POST['player7'],$_POST['player8']);
$kibitz = $_POST['kibitz']; if($kibitz != 1) $kibitz = 0; // unchecked so make it false
$now = date("Y-m-d H:i:s");
// GET HOST SETTINGS
require_once('player_settings.php');

// CREATE THE GAME TABLE
// Get next game number.  this is wa too complicated, need to trim.
$sql = "SELECT * FROM game_id_seq";
$old_id = get_one($sql);
		
$id = $old_id + 1;
// Damn, this is a complicated way to do a simple thing.   may re-work some of this
$sql = "UPDATE `game_id_seq` SET `id` = $id WHERE `id` = $old_id LIMIT 1 ;";
$q = mysql_query($sql) or die('Could not update the next game increment. '.mysql_error());

//$id = $DB->nextID('game_id'); 
$sql = "CREATE TABLE game_$id "
. '( id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, '
. 'player VARCHAR( 32 ) NOT NULL, '
. 'host SMALLINT NOT NULL, '
. 'state VARCHAR( 16 ) NOT NULL, '
. 'password VARCHAR( 32 ) NOT NULL, '
. 'color VARCHAR( 128 ) NOT NULL, '
. 'cards VARCHAR( 255 ) NOT NULL, '
. 'attackcard SMALLINT NOT NULL, '
. 'states BLOB NOT NULL, '
. 'armies SMALLINT UNSIGNED NOT NULL, '
. 'diplomacy VARCHAR( 255 ) NOT NULL, '
. 'messages BLOB NOT NULL, '
. 'portrait VARCHAR( 255 ) NOT NULL, '
. 'email VARCHAR( 255 ) NOT NULL, '
. 'mail_updates SMALLINT NOT NULL, '
. 'time DATETIME NOT NULL, '
. 'timestamp INT ( 15 ) NOT NULL, '
. 'PRIMARY KEY (id) )';

$q = mysql_query($sql) or die('Could not create new game: '.mysql_error());

$sql = "CREATE TABLE gamechat_$id (
		     id mediumint(7) NOT NULL AUTO_INCREMENT,
		     time bigint(11) DEFAULT '0' NOT NULL,
		     name tinytext NOT NULL,
		     text text NOT NULL,
		     url text NOT NULL,
		     UNIQUE KEY id (id)
		    );";

$q = mysql_query($sql) or die('Could not create chatroom for game: '.mysql_error());

$sql = "INSERT INTO gamechat_$id (time,name,text,url) VALUES ('".time()."','Game Started','".date('M-d-Y H:i:s')."','')";
$q = mysql_query($sql) or die('Could not create chatroom for game: '.mysql_error());

// ADD WORLD_DATA PLAYER
//$sql = 'SELECT id FROM countries'; // for the purpose of cards divide by 3 for each type; 1 2 and 3 are the three types and 0 is wild
//$q = mysql_query($sql) or die('Could not Add World Data: '.mysql_error());
//while ( $row = mysql_fetch_assoc($q)) { $results = $row; }

$cards = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43";
$results = string_2_array($cards);
//shuffle cards and write to string
shuffle($results); 
$cards = array_2_string($results); 
// add data for card turn in bonuses
$cardbonus = '4,6,8,10,12,15,20,25,30,35,40,45,50,55,60';
// add data for available colors
$possible_colors = array('purple','red','blue','green','black','teal','yellow','grey');
foreach ($possible_colors as $acolor){
	if($acolor != $color){
		$colors[] = $acolor;
	}
}
$colors_string = array_2_string($colors);
// player0 holds game state, if the game has begun or if it is over or if its the beginning proceeding or if it is turn time
// 	possible values:  join,setup,play,end          player holds whose turn it is (if turn-based)
// insert

$sql = "INSERT INTO game_$id (id,player,host,state,password,color,cards,attackcard,states,armies,diplomacy,messages,portrait,email,mail_updates,time)"
	." VALUES ('',0,0,'unused..','','$colors_string','$cards',0,'',0,'$cardbonus','','','',0,NOW())";
$q = mysql_query($sql) or die('Could not Insert Admin player: '.mysql_error());

// admin pass set as hosts pass // make sure noone can login this

// require_once('hash.php'); // fix hash functions
$hname = $_SESSION['player_name'];
$hpass = $_SESSION['player_pass'];
// insert Host Player
$sql = "INSERT INTO game_$id (player,host,state,password,color,cards,attackcard,states,armies,diplomacy,messages,portrait,email,mail_updates,time)"
		." VALUES ('$hname',1,'waiting','$hpass','$color','',0,'',0,'','','$portraiturl','$email','$updates',NOW())"; // diplomacy will hold cardbonus value
$q = mysql_query($sql) or die('Could not Insert Host player: '.mysql_error());

// CREATE GAME REFERENCE IN GAMES TABLE
$sql = "INSERT INTO games (id,name,mode,type,players,capacity,kibitz,password,state,time,timelimit) "
        ." VALUES ($id,'$gamename','$gamemode','$gametype',1,$numplayers,$kibitz,'$gamepass','Waiting',NOW(), '$timelimit')";
$q = mysql_query($sql) or die('Could not Update game reference table: '.mysql_error());


// SEND EMAILS
$domain = $_SERVER['SERVER_NAME'];
if ($_SERVER['SERVER_PORT'] != 80) // allow invites for non-standard port address' in invite link
    $domain .= ':'.$_SERVER['SERVER_PORT'];
$enc_gamepass = rawurlencode($gamepass);
$joinlink = "http://{$domain}/netrisk/joingame.php?id={$id}&pass={$enc_gamepass}";
foreach ($players as $player)
	if($player)   // NEED TO PARSE PASSWORD - FOR URL and then DECODE ON OTHER END
		mail($player, 'NetRisk Game Invitation', "::This is an automated message please do not reply::<br><br>"
												. "You have been invited to join the NetRisk game \"{$gamename}\".<br>"
												. "This game is $gametype style and will be played with $gamemode turns.<br><br>"
												. "If you wish to join this game click the link below <br><a href=\""
												. "{$joinlink}\">{$joinlink}</a>.",
     											"MIME-Version: 1.0\r\n"
												. "Content-type: text/html; charset=iso-8859-1\r\n"
												. "From: NetRisk@{$_SERVER['SERVER_NAME']}\r\n" 
												. "Reply-To: NetRisk@{$_SERVER['SERVER_NAME']}\r\n"
												. "X-Mailer: PHP/" . phpversion());
//var_dump($sql_data);
header("Location: ../index.php?page=gamebrowser");
?>
