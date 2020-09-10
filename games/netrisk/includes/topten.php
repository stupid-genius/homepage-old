<?php

    require_once('includes/_db.config.php');

// query.   must have defeated at least 1 player    
$sql = "SELECT id,login,lose,win FROM `users` WHERE `lose` > '1' AND `win` > '1'";
$result = mysql_query($sql);

		$i = 1; 
		$ranks = array();
	// go through array, filter out losers.
	while($row = mysql_fetch_array($result))  {
		// cant have a negative rank and make the top ten.
		if(($row['lose'] / $row['win']) > 0) {
				// Players defeated by Games played
			$ranks[$i]['record'] = $row['win'] / $row['lose'];
			$ranks[$i]['login'] = $row['login'];
			$ranks[$i]['uid'] = $row['id'];
			$i++;
		}
}

		// sort it highest to lowest
		rsort($ranks);

?>
<div id="top_ten" class="sidebar" style="position:relative; left: -4px;">
<h2>Player Rankings</h2>
</div>


<!-- Iturzaeta - New Scroll Bar -->
<div id="top_ten" class="sidebar" style="position:relative; left: -4px;overflow: auto; height: 210px; width: 220px;">


    <table>
    	<colgroup class="stats_rank" />
    	<colgroup class="stats_name" />
    	<colgroup class="stats_wins" />
        <tr>
    	    <th>Rank</th>
            <th>Player</th>
        </tr>
        <?php
            $rownum = 0;
	    foreach($ranks as $value) {
		    if($rownum < 10) {

	?><tr class="<?php if ($rownum%2) { echo "line_2"; } ?>">
                <td>
                    <?php echo number_format(($value['record']/10),3); ?>
                </td>
				<td style="text-align:left;">
				<a href="index.php?page=profile&pid=<?php echo ($value['uid']); ?>"> 
				<?php echo ($value['login']); ?></a>
				</td>
            </tr>
            
            <?php
		      //echo "\n";
                $rownum++;
		    } 
            } 
        ?>
    </table>
</div>    
