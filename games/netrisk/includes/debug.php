
<html>
<head>
</head>
<body>
<p>
<?php
//require_once('session.php');
require_once('_db.config.php');
require_once('functions.php');


// save rate GET variable
//$matches = array();
//preg_match('/rate=[0-9]*/', $_SERVER['HTTP_REFERER'], $matches);
//if (count($matches))
//    $gets = $matches[0];
//else $gets = '';

// make sure the player is an a placement state
/*MDW: copied from addarmies. edited*/

//$sql = 'SELECT state FROM game_13 WHERE player = hans';
$sql = 'SELECT states FROM game_13 WHERE id=3';
$pstate = get_one($sql);

if(DB::isError($pstate))
	die($pstate->getMessage());
//$pstate = string_2_array($pstate);
$pstate = '16+1,19+6,20+7,18+1,15+7,17+1,35+3';
$sql = "UPDATE game_13 SET states = '$pstate' WHERE id = 3"; // update new states with new armies in DB
$q = mysql_query($sql);
?>

<?php
    echo $sql;
?>
</p>
</body>

