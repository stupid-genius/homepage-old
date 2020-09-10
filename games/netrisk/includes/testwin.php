<?php
require_once('_db.config.php');

/*		 Add a 'win' to the winners*/
	// first, find out who the winner is.
		$sql = 'SELECT player FROM game_15 WHERE id > 0 AND state != \'dead\'';
//		$sql = 'SELECT player FROM game_'.$_SESSION['game_id'].' WHERE id > 0 AND state != \'dead\'';
	 	$winner = get_one($sql);
	// Get current number of 'win's, then add 1.
		$sql = 'SELECT win FROM users WHERE login = \''.$winner.'\'';
		$wins = single_qry($sql);
		$wins = mysql_result($wins, 0);
			$totalwins = intval($wins);
			$totalwins++;
	// Then give that user an additional 'win'
		$sql = 'UPDATE users SET win = \''.$totalwins.'\' WHERE login = \''.$winner.'\'';
		$result = single_qry($sql);
/*		End add a 'win' */	

echo $wins.'<br />';
echo $totalwins.'<br/>';
$totalwins++;
echo $totalwins.'<br/>';
$totalwins++;
echo $totalwins.'<br/>';
$totalwins++;
echo $totalwins.'<br/>';
$totalwins++;
?>
