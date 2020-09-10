<div id="nav">
    <?php if ($_SESSION['player_rank'] == 70 ){ ?>                                          
        <a href="index.php?page=admin/index">Admin Page</a>
    <?}?> 
        <a href="index.php?page=news">News</a> 
        <a href="index.php?page=gamebrowser">Browser</a>
        <a href="index.php?page=forum">Forum (local)</a>
	<a href="../forums/">Forum @ RF</a>
    <?php /*|<a href="index.php?page=source">Source</a>*/ ?>
    <?php if (isset($_SESSION['player_name'])) { ?>                                          
        <a href="index.php?page=includes/memberlist">Memberlist</a>
        <a href="index.php?page=profile">Profile</a>
    <?}?>
</div>

