<?
require_once('_db.config.php');

if (!isset($_GET['rate']) || $_GET['rate'] == 0) {
    $reload = false;
} else {
    $reload = true;
    $refreshrate = $_GET['rate'];
    // protect against too low refresh rates..
    if ($refreshrate < 5) $refreshrate = 5;
}

// Get players messages
$sql = 'SELECT messages FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
$playermsgs = get_one($sql);
?>
<html>
<head>

<link href="../css/game.css" rel="stylesheet" type="text/css" />
<? if ($reload) { ?>
<noscript><meta http-equiv="refresh" content="<?= $refreshrate ?>"></noscript>
<script language="JavaScript">
<!--

var sURL = unescape(window.location.pathname);

function doLoad(){
    setTimeout( "refresh()", <?= $refreshrate ?>*1000 );
}
function refresh(){
    window.location.href = sURL;
}
//-->
</script>
<script language="JavaScript1.1">
<!--
function refresh(){
    window.location.replace( sURL );
}
//-->
</script>
<script language="JavaScript1.2">
<!--
function refresh(){
    window.location.reload( false );
}
//-->
</script>
<? } ?>
</head>
<body <? if ($reload) echo 'onload="doLoad()"' ?> >
<span class="chat"><?= $playermsgs ?></span>
</body>
</html>
