<?php

require_once('_db.config.php');
require_once('functions.php');

$player = $_GET['player'];

if ($_SESSION['game_host'] != 1) {
   echo "you are not the game's host.";
   exit;
   }
   
   $sql = "UPDATE `game_".$_SESSION['game_id']."` SET `state` = 'dead' WHERE `player` = '$player' LIMIT 1 ";
   $r = single_qry($sql);
   
header("Location: ".$_SERVER['HTTP_REFERER']);
?>


