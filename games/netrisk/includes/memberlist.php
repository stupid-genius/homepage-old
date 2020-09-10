<?php

require_once('includes/_db.config.php');

//$sql = "SELECT * FROM users ORDER BY 'id' ASC LIMIT 1 , 30";  //leave out admin

if(isset($_GET['pagenumber']))
    $disppage = $_GET['pagenumber'];
else
    $disppage = 1;

$lowerlimit = (($disppage - 1)*30)+1;

$sql = "SELECT * FROM users ORDER BY 'id' ASC LIMIT ".$lowerlimit.", 30";  //leave out admin
$result = single_qry($sql);

?>
<table id="memberlist" border="0">
	<colgroup class="mem_rank" />
	<colgroup class="mem_icon" />
	<colgroup class="mem_name" />
	<colgroup class="mem_wins" />
<tr>
	<th>id</th>
	<th>Icon</th>
	<th>User</th>
	<th>Record</th>
</tr>
<?php
$rownum = 0;
while ($mem = mysql_fetch_array($result)) {
	?><tr class="<? if ($rownum%2) { echo "line_2"; }?>">
<td><?php
                echo "<a href=\"".$gamepath."index.php?page=profile&pid=".$mem['id']."\">".$mem['id']."</a>";
	?></td>
                <td><?php
                    if($mem['image_type'] == "")
                        echo "<img src=\"images/blank.gif\">";
                    else
                        echo "<img src=\"player_avatar.php?image_id=".$mem['id']."\">";
                ?></td>
                <td style="text-align:left"><?php 	
		echo "<a href=\"".$gamepath."index.php?page=profile&pid=".$mem['id']."\">".$mem['login']."</a>";
	?></td><td><?php
		// IF user has not killed anyone, he is not ranked.
		if ($mem['win'] > 0) {
			echo "<a href=\"".$gamepath."index.php?page=profile&pid=".$mem['id']."\">".number_format($mem['lose']/$mem['win'],2)."</a>";
		} else { 
			echo "<i> - not ranked -</i>";
		}
	?></td></tr><?php
		echo "\n";
		$rownum++;

} ?>
</table>
<div style="float: right;">
<?php
if($disppage > 1)
  echo "<a href=\"".$gamepath."index.php?page=includes/memberlist&pagenumber=".($disppage-1)."\"><< Prev</a> | ";
if($rownum == 30) 
  echo "<a href=\"".$gamepath."index.php?page=includes/memberlist&pagenumber=".($disppage+1)."\">Next >></a>";

?>
</div>
