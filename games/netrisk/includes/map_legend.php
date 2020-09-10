<?php

require_once('_db.config.php');

$sql = "SELECT * FROM continents";
$result = mysql_query($sql);
?>
Continent Values<br />
<table class="map_legend">
<?php
while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
   printf ("<tr><td> %s </td><td> %s </td></tr>", $row['bonus'], $row['name']);
}

?></table>
