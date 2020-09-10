<?php
require_once('includes/_db.config.php');
if ($_SESSION['player_rank'] != '70') exit;
?><center>
<table style="background-color: black; border: 1px inset #999;">
<tr><th>admn</th>
<th>ID</th>
<th>Rank</th>
<th>Login</th>
<th>Email</th>
<th>Win</th>
<th>Lose</th>
<th>del</th></tr>

<?php


$sql = "select * from users";
$result = single_qry($sql);

while ($row = mysql_fetch_assoc($result))
{
if ($row["rank"] == '70'){ 
$ifadmin = "<a href='admin/edit.php?mod=rmadmn&user=".$row['id']."'>rm admn</a>"; 
} else { $ifadmin = "<a href='admin/edit.php?mod=admn&user=".$row['id']."'>+ admin</a>";
}
?><tr class="<?php if ($i%2) { echo "line_2"; }?>"> 
<td><?= $ifadmin ?></td>
<td><?= $row["id"]?></td>
<td><?= $row["rank"]?></td>
<td><?= $row["login"]?></td>
<td><?= $row["email"]?></td>
<td><?= $row["win"]?></td>
<td><?= $row["lose"]?></td>
<td><a style='color:red;font-weight:800;' href='admin/edit.php?mod=delusr&user=<?= $row["id"]?>'>X</a></td>
</tr>
<?php
$i++;
}
?>
</table></center>


