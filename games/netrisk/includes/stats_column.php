<?php

require_once('includes/_db.config.php');

$sql = "SELECT * FROM users WHERE rank != 70 ORDER BY 'rank' DESC LIMIT 0 , 10";
$result = single_qry($sql);

?><b> Top Rankings</b><br />
<table id="stats_browse" border="0">
	<colgroup class="stats_rank" />
	<colgroup class="stats_name" />
	<colgroup class="stats_wins" />
<tr>
	<th>R</th>
	<th>User</th>
	<th>W</th>
</tr>
<?php
$rownum = 0;
while ($stat = mysql_fetch_array($result)) {
	?><tr><td><?
		echo $stat['rank'];
	?></a></td><td style="text-align:left"><? 	
		echo $stat['login'];
	?></td><td><?
		echo $stat['win'];
	?></td></tr><?
		echo "\n";
		$rownum++;
} ?>
</table>

