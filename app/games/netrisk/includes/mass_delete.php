<?php
require_once('_db.config.php');

$id = $_SESSION['chatid'];

$sql = 'DELETE FROM chat WHERE id = '.$id.' LIMIT 1';
$q = single_qry($sql);

header("Location: ../index.php?page=includes/mass_browser"); 

?>

