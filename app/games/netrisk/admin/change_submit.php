<?php
require_once('../includes/_db.config.php');

$username = $_GET['username'];
$pass = md5($_GET['new_pass']);

$sql = "UPDATE users SET pass = \"$pass\" WHERE login=\"$username\" LIMIT 1" ;

$result = single_qry($sql);


echo "username->".$username." ..... ".$_POST['username'];
echo "password->".$pass." ..... ".$_POST['new_pass'];

?>

