<?php
require_once('../includes/_db.config.php');
if ($_SESSION['player_rank'] != '70') exit;

$mod = $_GET['mod'];
$user = $_GET['user'];
$game = $_GET['game'];

if ($mod == 'rmadmn'){
	$sql = "UPDATE users SET rank = 0 WHERE id = $user";
	$result = single_qry($sql);
}

if ($mod == 'admn') {
	$sql = "UPDATE users SET rank = 70 WHERE id = $user";
	$result = single_qry($sql);
}

if ($mod == 'delusr') {
	$sql = "DELETE FROM users WHERE id = $user LIMIT 1";
	$result = single_qry($sql);
}

if ($mod == 'delgame') {
	$sql = "DROP TABLE game_".$game;
	$result = single_qry($sql);
	$sql2 = "DELETE FROM games WHERE id = ".$game." LIMIT 1";
	$result2 = single_qry($sql2);
	$sql3 = "DROP TABLE gamechat_".$game;
	$result3 = single_qry($sql3);
}

if ($mod == 'delchat') {
	$sql = "DELETE FROM chat WHERE id = $game LIMIT 1";
	$result = single_qry($sql);
}
header("Location: ".$_SERVER['HTTP_REFERER']);
?>
