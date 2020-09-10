<?php
if (isset($_SESSION['attack_rolls'])) {

?>
<div class="dice_area" id="dice_area">
<center><table> 
<?php
foreach ($_SESSION['attack_rolls'] as $key => $val) {
   echo "<td id='dice_roll_a'>" . $val . "</td>";
}
?>
</table><table>
<?php
foreach ($_SESSION['defend_rolls'] as $key => $val) {
   echo "<td id='dice_roll_d'>" . $val . "</td>";
}

?></table></center>
	</div>

<?php } ?>

