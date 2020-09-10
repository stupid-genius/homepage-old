<?php
require_once('_db.config.php');

$id = $_SESSION['game_id'];

$sql = 'DROP TABLE game_'.$id;
$q = single_qry($sql);

$sql = 'DROP TABLE gamechat_'.$id;
$q = single_qry($sql);

$sql2 = 'DELETE FROM games WHERE id = '.$id.' LIMIT 1';
$q = single_qry($sql2);

header("Location: ../index.php?page=gamebrowser"); 

?>

