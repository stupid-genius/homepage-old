<?
require_once('_db.config.php');
$id = $_SESSION['chatid'];

// Get players messages
$sql = "SELECT * FROM chat WHERE id =".$id;
$messages = get_assoc($sql);

?>
<head>
<link href="../css/game.css" rel="stylesheet" type="text/css" />
</head>
<span class="chat"><?= $messages["messages"] ?></span>

