<?php
/* ********** Game LOG ************  */
require_once('_db.config.php');
require_once('functions.php');

$sql = "SELECT messages FROM game_".$_SESSION['game_id']." WHERE id = 1";
$gamelog = get_one($sql) or die('The game has not started.');

?>
<html>
<head>

<link href="../css/base.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class='gamelog'>
<?php
if (player_state()=='Initial Placement') {
  echo "Log does not display during initial placement";
} else {
  echo $gamelog;
} 
?>
</div>
</body>
