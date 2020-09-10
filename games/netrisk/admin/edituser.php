<?php
require_once('../includes/_db.config.php');
if ($_SESSION['player_rank'] != '70') exit;

$mod = $_GET['mod'];
$user = $_GET['user'];

if ($mod == 'rmadmn'){
	$sql = "UPDATE users SET rank = 0 WHERE id = $user";
	$result = single_qry($sql);
}

if ($mod == 'admn') {
	$sql = "UPDATE users SET rank = 70 WHERE id = $user";
	$result = single_qry($sql);
}

if ($mod == 'del') {
	$sql = "DELETE FROM users WHERE id = $user LIMIT 1";
	$result = single_qry($sql);
}

header("Location: ../index.php?page=admin/memberlist");
?>
