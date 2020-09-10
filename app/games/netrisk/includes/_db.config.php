<?php 
require_once('functions_sql.php');
require_once('riskconfig.php');

// Connect to host and access database
$conn = mysql_connect ($dbhost, $dbuser, $dbpass) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ($dbname) or die('Could not select that databse');

session_name("netrisk_$gamename");
session_start();


?>
